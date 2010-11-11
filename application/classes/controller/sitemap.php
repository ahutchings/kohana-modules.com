<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sitemap extends Controller
{
    public function action_index()
    {
        $cache = Cache::instance();

        if (($response = $cache->get('sitemap')) === NULL)
        {
            $sitemap = new Sitemap;

            foreach (ORM::factory('module')->find_all() as $module)
            {
                $url = new Sitemap_URL;

                $url->set_loc(url::site("modules/$module->username/$module->name", TRUE))
                    ->set_last_mod($module->updated_at)
                    ->set_change_frequency('weekly')
                    ->set_priority(1);

                $sitemap->add($url);
            }

            $response = $sitemap->render();

            $cache->set('sitemap', $response, 86400);
        }
        
        echo $response;
    }
}
