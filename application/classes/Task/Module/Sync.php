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
	protected function _execute(array $params)
	{
        $modules = ORM::factory('Module')->find_all();

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
        }
	}
}
