<?php

/**
 * Flags modules that have been removed from GitHub.
 */
class Minion_Task_Module_FlagDeleted extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
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
}
