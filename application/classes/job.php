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
    
    public static function fetch_gitmodules($url)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        return $matches;
    }
}
