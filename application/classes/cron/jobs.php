<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class container for static Cron jobs.
 */
class Cron_Jobs
{
    /**
     * Imports new repositories and flags deleted repositories from the
     * master module repository.
     */
    public static function sync_index()
    {
        $url     = "https://github.com/ahutchings/kohana-modules/raw/master/.gitmodules";
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        // set flagged_for_deletion_at on all modules in database, but require manual pruning
        DB::update('modules')->set(array('flagged_for_deletion_at' => time()));
        
        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $count = ORM::factory('module')
                ->where('username', '=', $matches['username'][$i])
                ->where('name', '=', $matches['name'][$i])
                ->count_all();
                
            if ($count == 1)
            {
                // module exists in .gitmodules, unflag for deletion
                DB::update('modules')
                    ->set(array('flagged_for_deletion_at' => NULL))
                    ->where('username', '=', $matches['username'][$i])
                    ->where('name', '=', $matches['name'][$i]);
            }
            else
            {
                $module = ORM::factory();
                $module->username = $matches['username'][$i];
                $module->name     = $matches['name'][$i];
                $module->save();
                
                $module->refresh_github_metadata();
            }
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
        }
    }
    
    /**
     * Fetches search results from GitHub and stores them locally.
     */
    public static function fetch_search_results()
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
}
