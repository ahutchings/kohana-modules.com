<?php defined('SYSPATH') or die('No direct script access.');

class Model_Module extends ORM
{
    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);

    protected $_sorting = array('name' => 'ASC');

    protected $_has_many = array
    (
        'kohana_versions' => array
        (
            'through' => 'module_compatibilities',
        )
    );

    protected $_rules = array
    (
        'name' => array('not_empty' => array()),
        'user' => array('not_empty' => array()),
    );

    protected $_filters = array
    (
        TRUE => array('trim' => array()),
    );

    public function __get($name)
    {
        if ($name == 'tags_array')
        {
            $tags = explode(':', $this->tags);
            rsort($tags);
            return array_filter($tags);
        }
        
        return parent::__get($name);
    }

    /**
     * Syncs the module's metadata from the GitHub repository.
     *
     * If a 404 exception is thrown by the GitHub API, the module is flagged
     * for deletion.
     *
     * @return  FALSE|NULL  FALSE if the module isn't found on GitHub, NULL otherwise
     */
    public function sync()
    {
        try
        {
            $repo = Github::instance()->getRepoApi()
                ->show($this->username, $this->name);
        }
        catch (phpGitHubApiRequestException $e)
        {
            if ($e->getCode() === 404)
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

        $this->description   = $repo['description'];
        $this->homepage      = $repo['homepage'];
        $this->forks         = $repo['forks'];
        $this->watchers      = $repo['watchers'];
        $this->fork          = $repo['fork'];
        $this->has_wiki      = $repo['has_wiki'];
        $this->has_issues    = $repo['has_issues'];
        $this->has_downloads = $repo['has_downloads'];
        $this->open_issues   = $repo['open_issues'];

        $repo_tags = Github::instance()->getRepoApi()->getRepoTags($this->username, $this->name);
        $tags = array_keys($repo_tags);

        $this->tags = empty($tags) ? NULL : implode(':', $tags);

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
}
