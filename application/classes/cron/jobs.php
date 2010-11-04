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
}
