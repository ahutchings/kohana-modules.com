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
                'time' => Cron::$_times[$name],
                'time_span' => Date::span(Cron::$_times[$name], NULL, 'hours,minutes,seconds'),
            );
        }
        
        return $jobs;
    }
    
    public static function next_run()
    {   
        return min(Cron::$_times);
    }
}
