<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sitemap extends Controller
{
    public function action_index()
    {
        $cache = Cache::instance();

        if ( ! $response = $cache->get('sitemap', FALSE))
        {
            $response = $this->_generate_sitemap();

            $cache->set('sitemap', $response, Date::DAY);
        }

        echo $response;
    }

    private function _generate_sitemap()
    {
        $sitemap = new Sitemap;

        $url = new Sitemap_URL;

        $url->set_loc(URL::base('http'))
            ->set_priority(1.0);

        $sitemap->add($url);

        // Add primary pages
        foreach (array('about', 'feedback', 'add-a-module') as $page)
        {
            $url = new Sitemap_URL;

            $url->set_loc(URL::site("pages/$page", TRUE))
                ->set_priority(0.8);

            $sitemap->add($url);
        }

        // Add module developer pages
        foreach (DB::select(DB::expr('DISTINCT username'))
            ->from('modules')->execute()->as_array() as $result)
        {
            $url = new Sitemap_URL;

            $url->set_loc(URL::site("modules/".$result['username'], TRUE))
                ->set_change_frequency('monthly')
                ->set_priority(0.5);

            $sitemap->add($url);
        }

        // Add individual module pages
        foreach (ORM::factory('Module')->find_all() as $module)
        {
            $url = new Sitemap_URL;

            $url->set_loc(URL::site("modules/$module->username/$module->name", TRUE))
                ->set_last_mod($module->updated_at)
                ->set_change_frequency('monthly')
                ->set_priority(0.5);

            $sitemap->add($url);
        }

        return $sitemap->render();
    }
}
