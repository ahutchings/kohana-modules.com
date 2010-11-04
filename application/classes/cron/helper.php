<?php defined('SYSPATH') or die('No direct script access.');

class Cron_Helper extends Cron
{
    public static function get_jobs()
    {
        $jobs = array();
        
        foreach(Cron::$_jobs as $name => $job)
        {
            $jobs[] = array
            (
                'name' => $name,
            );
        }
        
        return $jobs;
    }
}
