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
        $modules = ORM::factory('module');
        $modules->flagged_for_deletion_at = time();    
        $modules->save_all();
        
        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $module = ORM::factory('module')
                ->where('username', '=', $matches['username'][$i])
                ->where('name', '=', $matches['name'][$i])
                ->find();
                
            if ($module->loaded())
            {
                // module exists in .gitmodules, unflag for deletion
                $module->flagged_for_deletion_at = NULL;
            }
            else
            {
                // @todo fetch and merge metadata
                $module->username = $matches['username'][$i];
                $module->name     = $matches['name'][$i];
            }
            
            $module->save();
        }
    }
    
    /**
     * Refreshes local repository metadata from GitHub.
     */
    public static function refresh_metadata()
    {
        // select 60 jobs with oldest metadata
        $modules = ORM::factory('module')
            ->where('updated_at', '<', time() - Date::WEEK)
            ->limit(60)
            ->find_all();

        foreach ($modules as $module)
        {
            $module->refresh_github_metadata();
        }
    }
}
