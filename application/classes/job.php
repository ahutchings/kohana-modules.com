<?php defined('SYSPATH') or die('No direct script access.');

class Job
{
    /**
     * Imports new repositories from the master module repository.
     */
    public static function import_from_master()
    {
        $matches = self::fetch_gitmodules("https://github.com/ahutchings/kohana-modules/raw/master/.gitmodules");

         for ($i = 0; $i < count($matches[0]); $i++)
         {
             $count = ORM::factory('module')
                 ->where('username', '=', $matches['username'][$i])
                 ->where('name', '=', $matches['name'][$i])
                 ->count_all();

             if ($count === 0)
             {
                 $module = ORM::factory('module');
                 $module->username = $matches['username'][$i];
                 $module->name     = $matches['name'][$i];
                 $module->save();

                 $module->refresh_github_metadata();

                 // throttle API requests
                 sleep(2);
             }
         }
    }
    
    /**
     * Imports new repositories from kolanos/kohana-universe.
     */
    public static function import_from_universe()
    {
        $matches = self::fetch_gitmodules("https://github.com/kolanos/kohana-universe/raw/master/.gitmodules");
        
        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $queue = ORM::factory('queue');
            $queue->username = $matches['username'][$i];
            $queue->name     = $matches['name'][$i];
            $queue->source   = Model_Queue::SOURCE_KOHANA_UNIVERSE;
            
            if ($queue->check())
            {
                $queue->save();
            }
        }
    }
    
    /**
     * Flags modules that have been removed from GitHub.
     */
    public static function flag_deleted()
    {
        foreach (ORM::factory('module')->find_all() as $module)
        {
            $url = 'https://github.com/'.$module->username.'/'.$module->name;
            
            if (Remote::status($url) === 404)
            {
                DB::update('modules')
                    ->set(array('flagged_for_deletion_at' => time()))
                    ->where('username', '=', $module->username)
                    ->where('name', '=', $module->name); 
            }
            
            // throttle HEAD requests
            sleep(2);
        }
    }
    
    /**
     * Refreshes local repository metadata from GitHub.
     */
    public static function refresh_metadata()
    {
        // select 30 jobs with oldest metadata
        $modules = ORM::factory('module')
            ->where('updated_at', '<', time() - Date::WEEK)
            ->order_by('updated_at', 'ASC')
            ->limit(30)
            ->find_all();

        foreach ($modules as $module)
        {
            $module->refresh_github_metadata();
            
            // throttle API requests
            sleep(2);
        }
    }
    
    /**
     * Fetches search results from GitHub and stores them locally.
     */
    public static function import_from_search()
    {
        for ($i = 1; $i < 10; $i++)
        {
            $results = Github::instance()
                ->getRepoApi()
                ->search('kohana', '', $i);

            foreach ($results as $result)
            {
                $queue = ORM::factory('queue');
                $queue->values($result);
                $queue->source = Model_Queue::SOURCE_GITHUB_SEARCH;

                if ($queue->check())
                {
                    $queue->save();   
                }
            }
            
            // throttle API requests
            sleep(2);
        }
    }
    
    /**
     * Delete search results that have been added to the module index.
     */
    public static function prune_queue()
    {
        $modules = ORM::factory('module')->find_all();
        
        foreach ($modules as $module)
        {
            $queue = ORM::factory('queue')
                ->where('username', '=', $module->username)
                ->where('name', '=', $module->name)
                ->find();
                
            if ($queue->loaded())
            {
                $queue->delete();
            }
        }
    }
    
    public static function fetch_gitmodules($url)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        return $matches;
    }
}
