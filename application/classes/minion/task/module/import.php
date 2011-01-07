<?php

/**
 * Imports new repositories from the master module repository.
 */
class Minion_Task_Module_Import extends Minion_Task
{
    /**
	 * A set of config options that this task accepts
	 * @var array
	 */
	protected $_config = array();
    
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
	{
	    $matches = self::fetch_gitmodules("https://github.com/ahutchings/kohana-modules/raw/master/.gitmodules");

         for ($i = 0; $i < count($matches[0]); $i++)
         {
             $count = ORM::factory('module')
                 ->where('username', '=', $matches['username'][$i])
                 ->where('name', '=', $matches['name'][$i])
                 ->count_all();

             if ($count === 0)
             {
                 $module = ORM::factory('module');
                 $module->username = $matches['username'][$i];
                 $module->name     = $matches['name'][$i];
                 $module->save();

                 $module->refresh_github_metadata();

                 // throttle API requests
                 sleep(2);
             }
         }
         
         self::prune_queue();
	}
	
	public static function fetch_gitmodules($url)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        return $matches;
    }
    
    /**
     * Delete search results that have been added to the module index.
     */
    public static function prune_queue()
    {
        $modules = ORM::factory('module')->find_all();
        
        foreach ($modules as $module)
        {
            $queue = ORM::factory('queue')
                ->where('username', '=', $module->username)
                ->where('name', '=', $module->name)
                ->find();
                
            if ($queue->loaded())
            {
                $queue->delete();
            }
        }
    }
}
