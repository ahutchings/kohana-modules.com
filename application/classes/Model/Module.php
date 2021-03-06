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
        $client = AuthenticatedGithubClient::instance();

        try
        {
            $repo = $client->api('repo')->show($this->username, $this->name);
            $tags = $client->api('repo')->tags($this->username, $this->name);
        }
        catch (Exception $e)
        {
            $deleted = $e instanceof Github\Exception\RuntimeException && $e->getCode() === 404;

            // If the module has been made private or deleted
            if ($deleted)
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

        //bookmarks AKA stars are named watchers in the repo
        $values['stars'] = $repo['watchers'];

        //get the watch count
        $subs = $client->getHttpClient()->get('repos/'.$this->username.'/'.$this->name.'/subscribers?per_page=30');

        //get the Link header
        $link = $subs->getHeader('Link');

        if ($link == null)
        {
            $values['watchers'] = count($subs->json());
        }
        else
        {
            //A link was supplied
            $links = explode(',', $link);

            $pattern = '/&page=(?P<pages>[0-9]*)/';
            preg_match_all($pattern, end($links), $m);

            $subs = $client->getHttpClient()->get('repos/'.$this->username.'/'.$this->name.'/subscribers?page='.$m['pages'][0].'&per_page=30');

            $values['watchers'] = (($m['pages'][0] - 1) * 30) + count($subs->json());
        }

        //check if composer is supported
        try
        {
          $res = $client->getHttpClient()->get('repos/'.$this->username.'/'.$this->name.'/contents/composer.json');
          $composer = true;
        }
        catch (Exception $e)
        {
          $composer = false;
        }

        $values['has_composer'] = $composer;
        $this->values($values);

        if (count($tags))
        {
            foreach ($tags as $tag)
            {
                $tag_values = array(
                    'module_id' => $this->id,
                    'name'      => $tag['name'],
                    );

                $tag = ORM::factory('Tag', $tag_values);

                if ( ! $tag->loaded())
                {
                    $tag->values($tag_values)->save();
                }
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
     * Query builder method to select modules that are compatible with a
     * particular Kohana version.
     *
     * @param   ORM|string  Kohana version model or name
     * @return  $this
     */
    public function where_compatible_with($version = 'any')
    {
        if ($version === 'any')
        {
            return $this;
        }

        if ( ! ($version instanceof Model_Kohana_Version))
        {
            // We have a version name
            $version = ORM::factory('Kohana_Version',
                array('name' => $version));
        }

        if ( ! $version->loaded())
            return $this;

        return $this
            ->join('module_compatibilities')
            ->on('module_compatibilities.module_id', '=', 'id')
            ->where('module_compatibilities.kohana_version_id', '=', $version->id);
    }

    public function isCompatibleWithKohanaVersion($version)
    {
        return $this->kohana_versions->where('name', '=', $version)->count_all() > 0;
    }

    public function filterBySearchTerm($term)
    {
        return $this
            ->where_open()
                ->where('name', 'LIKE', "%$term%")
                ->or_where('description', 'LIKE', "%$term%")
                ->or_where('username', 'LIKE', "%$term%")
            ->where_close();
    }
}
