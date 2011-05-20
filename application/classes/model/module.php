<?php defined('SYSPATH') or die('No direct script access.');

class Model_Module extends ORM
{
    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);

    protected $_sorting = array('name' => 'ASC');

    protected $_has_many = array
    (
        'kohana_versions' => array('through' => 'module_compatibilities'),
        'tags'            => array(),
    );

    public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                ),
            'username' => array(
                array('not_empty'),
                ),
            );
    }

    public function filters()
    {
        return array(
            TRUE => array(
                array('trim'),
                ),
            );
    }
    
    /**
     * @var  array  fields to import from the GitHub repository API
     */
    protected $_import_fields = array
    (
        'description',
        'homepage',
        'fork',
        'forks',
        'watchers',
        'has_wiki',
        'has_issues',
        'has_downloads',
        'open_issues',
    );

    /**
     * Syncs the module's metadata from the GitHub repository.
     *
     * If a 404 exception is thrown by the GitHub API, the module is flagged
     * for deletion.
     *
     * @return  boolean  FALSE if the module isn't found on GitHub, TRUE otherwise
     */
    public function sync()
    {
        try
        {
            $repo = Github::instance()->getRepoApi()
                ->show($this->username, $this->name);

            $tags = Github::instance()->getRepoApi()
                ->getRepoTags($this->username, $this->name);
        }
        catch (phpGitHubApiRequestException $e)
        {
            // If the module has been made private or deleted
            if (in_array($e->getCode(), array(401, 404)))
            {
                // Flag the module for deletion.
                $this->flagged_for_deletion_at = time();
                $this->save();

                return FALSE;
            }
            else
            {
                // Rethrow the exception.
                throw $e;
            }
        }

        $values = Arr::extract($repo, $this->_import_fields);

        $this->values($values);

        foreach (array_keys($tags) as $tag_name)
        {
            $tag_values = array(
                'module_id' => $this->id,
                'name'      => $tag_name,
                );
            
            $tag = ORM::factory('tag', $tag_values);

            if ( ! $tag->loaded())
            {
                $tag->values($tag_values)->save();
            }
        }

        $this->save();

        return TRUE;
    }

    /**
     * Returns the specified GitHub URL for the module.
     *
     * @param   string  URL type
     * @return  string
     */
    public function url($type = NULL)
    {
        switch ($type)
        {
            case 'username':
                return "https://github.com/$this->username";
            case 'wiki':
                return "https://github.com/$this->username/$this->name/wiki";
            case 'issues':
                return "https://github.com/$this->username/$this->name/issues";
            case 'homepage':
                if (strpos($this->homepage, '://') === FALSE)
                    return "http://$this->homepage";
                    
                return $this->homepage;
            default:
                return "https://github.com/$this->username/$this->name";
        }
    }
    
    /**
     * Sets the column name to order by from the query string.
     *
     * @return  $this
     */
    public function set_order_by()
    {
        // Get the selected sort method
        $order_by = Arr::get($_GET, 'sort', 'watchers');
        
        // Valid sort methods
        $sort_methods = array
        (
            'watchers' => 'watchers',
            'forks'    => 'forks',
            'added'    => 'created_at',
        );

        if ( ! in_array($order_by, array_keys($sort_methods)))
        {
            // Order by watchers if the selected sorting is not valid.
            $sort_column = 'watchers';
        }
        else
        {
            // Map the sort method to the database column name
            $sort_column = $sort_methods[$order_by];
        }
        
        return $this->order_by($sort_column, 'DESC');
    }
}
