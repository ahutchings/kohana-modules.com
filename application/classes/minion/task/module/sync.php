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
	    // Modules that haven't been refreshed in the past week
        $modules = ORM::factory('module')
            ->where('updated_at', '<', time() - Date::WEEK)
            ->find_all();

        foreach ($modules as $module)
        {
            $success = $module->sync();

            $message = "Refreshed metadata for $module->username/$module->name.";
            
            if ( ! $success)
            {
                $message += " (404)";
            }

            $this->log($message);

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
	protected function log($message)
	{
		fwrite(STDOUT, $message.PHP_EOL);
	}
}
