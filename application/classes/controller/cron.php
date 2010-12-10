<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Kohana_Controller_Cron
{
    /**
     * Imports new repositories from the master module repository.
     */
    public function action_import_from_master()
    {
        Job::import_from_master();
    }

    /**
     * Imports new repositories from kolanos/kohana-universe.
     */
    public function action_import_from_universe()
    {
        Job::import_from_universe();
    }
    
    /**
     * Flags modules that have been removed from GitHub.
     */
    public function action_flag_deleted()
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
    public function action_refresh_metadata()
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
    public function action_import_from_search()
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
    public function action_prune_queue()
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
}
