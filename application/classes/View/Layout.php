<?php defined('SYSPATH') or die('No direct script access.');

class View_Layout
{
    public function site_name()
    {
        return Arr::get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']);
    }

    public function tagline()
    {
        return __('Indexing <span>:modules</span> modules from <span>:developers</span> developers.',
            array(
                ':modules' => $this->getModuleCount(),
                ':developers' => $this->getDeveloperCount()
                )
            );
    }

    private function getModuleCount()
    {
        return ORM::factory('Module')->count_all();
    }

    private function getDeveloperCount()
    {
        return DB::query(Database::SELECT, 'SELECT DISTINCT username FROM modules')
            ->execute()
            ->count();
    }

    public function meta_description()
    {
        return strip_tags($this->tagline());
    }

    public function query()
    {
        return Arr::get($_GET, 'query');
    }

    public function title()
    {
        return $this->title;
    }

    public function page_title()
    {
        $title = $this->title();

        if ($title)
        {
            $page_title = $title.' - '.$this->site_name();
        }
        else
        {
            $page_title = $this->site_name();
        }

        return $page_title;
    }

    public function kohana_version()
    {
        return Kohana::VERSION;
    }

    public function include_google_analytics()
    {
        return Kohana::$environment === Kohana::PRODUCTION;
    }

    public function prevent_page_indexing()
    {
        return isset($_GET['query']) OR isset($_GET['page']);
    }

    public function recently_added()
    {
        $recently_added = ORM::factory('Module')
            ->limit(7)
            ->order_by('created_at', 'DESC')
            ->find_all()
            ->as_array();

        return array_map(array($this, 'formatRecentlyAddedModuleCreatedAt'), $recently_added);
    }

    private function formatRecentlyAddedModuleCreatedAt($module)
    {
        $module->created_at = date(DATE_ISO8601, $module->created_at);
        return $module;
    }

    public function prolific_authors()
    {
        return DB::select('username', DB::expr('COUNT(1) as module_count'))
            ->from('modules')
            ->limit(7)
            ->order_by('module_count', 'DESC')
            ->group_by('username')
            ->as_object()
            ->execute();
    }
}
