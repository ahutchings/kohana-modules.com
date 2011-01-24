<?php

/**
 * Refreshes local repository metadata from GitHub.
 */
class Minion_Task_Module_Sync extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
	{
	    // jobs that haven't been refreshed in the past week
        $modules = ORM::factory('module')
            ->where('updated_at', '<', time() - Date::WEEK)
            ->find_all();

        foreach ($modules as $module)
        {
            $this->log("Refreshing metadata for $module->username/$module->name...", FALSE);

            $success = $module->sync();

            $this->log($success ? 'done.' : 'done (404).', TRUE);

            // throttle API requests
            sleep(2);
        }
	}
	
	/**
	 * Writes the message to STDOUT.
	 *
	 * @param   string  Message
	 * @return  void
	 */
	protected function log($message, $new_line = TRUE)
	{
	    if ($new_line)
	        $message = $message.PHP_EOL;
	    
		fwrite(STDOUT, $message);
	}
}
