<?php

/**
 * Refreshes local repository metadata from GitHub.
 */
class Task_Module_Sync extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $params)
	{
	    // Modules that haven't been refreshed in the past week
        $modules = ORM::factory('module')
            ->where('updated_at', '<', time() - Date::WEEK)
            ->find_all();

        foreach ($modules as $module)
        {
            if ($success = $module->sync())
            {
                Minion_CLI::write("$module->username/$module->name synced.");
            }
            else
            {
                Minion_CLI::write("$module->username/$module->name flagged for deletion.", 'red');
            }

            // throttle API requests
            sleep(2);
        }
	}
}
