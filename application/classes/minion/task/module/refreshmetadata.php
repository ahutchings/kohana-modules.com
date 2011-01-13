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
            $this->log("Refreshing metadata for $module->username/$module->name...", FALSE);
            
            $return = $module->refresh_metadata();
            
            if ($return === FALSE)
                $this->log("404.");
            else
                $this->log("success!", TRUE);
            
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
