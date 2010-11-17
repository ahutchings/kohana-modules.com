<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Kohana_Controller_Cron
{
    /**
     * Imports new repositories from the master module repository.
     */
    public function action_import_new_modules()
    {
        $url     = "https://github.com/ahutchings/kohana-modules/raw/master/.gitmodules";
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
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
     * Flags modules that have been removed from GitHub.
     */
    public function action_flag_deleted_modules()
    {
        foreach (ORM::factory('module')->find_all() as $module)
        {
            $url = 'http://github.com/'.$username.'/'.$name;
            
            if (Remote::status($url) == 404)
            {
                DB::update('modules')
                    ->set(array('flagged_for_deletion_at' => time()))
                    ->where('username', '=', $username)
                    ->where('name', '=', $name); 
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
    public function action_fetch_search_results()
    {
        $repositories = Github::instance()
            ->getRepoApi()
            ->search('kohana');
        
        $modules = DB::select('username', 'name')
            ->from('modules')
            ->execute();
        
        $results = self::filter_existing($repositories, $modules);
        
        // truncate existing database
        ORM::factory('searchresult')->delete_all();
        
        // insert new rows
        foreach ($results as $result)
        {
            $searchresult = ORM::factory('searchresult');
            $searchresult->values($result);
            $searchresult->save();
        }
    }
    
    private static function filter_existing($repositories, $modules)
    {
        $repositories_nonassoc = array();
        $modules_nonassoc = array();
        
        foreach ($repositories as $repository)
        {
            $repositories_nonassoc[$repository['username'].'/'.$repository['name']] = $repository['description'];
        }
        
        foreach ($modules as $module)
        {
            $modules_nonassoc[$module['username'].'/'.$module['name']] = '';
        }
        
        $filtered = array_diff_key($repositories_nonassoc, $modules_nonassoc);
        
        return self::reassoc($filtered);
    }
    
    private static function reassoc($array)
    {
        $return = array();
        
        foreach ($array as $k => $description)
        {
            list($username, $name) = explode('/', $k);
            
            $return[] = array
            (
                'username'    => $username,
                'name'        => $name,
                'description' => $description,
            );
        }
        
        return $return;
    }
    
    /**
     * Delete search results that have been added to the module index.
     */
    public function action_prune_search_results()
    {
        $modules = ORM::factory('module')->find_all();
        
        foreach ($modules as $module)
        {
            $searchresult = ORM::factory('searchresult')
                ->where('username', '=', $module->username)
                ->where('name', '=', $module->name)
                ->find();
                
            if ($searchresult->loaded())
            {
                $searchresult->delete();
            }
        }
    }
}
