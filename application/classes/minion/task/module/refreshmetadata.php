<?php

/**
 * Refreshes local repository metadata from GitHub.
 */
class Minion_Task_Module_RefreshMetadata extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
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
}
