<?php

/**
 * Imports new repositories from the master module repository.
 */
class Minion_Task_Module_Import extends Minion_Task
{   
    protected $_branches = array();
    
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
	{
	    $this->_fetch_branches();
	    $this->_create_kohana_versions();
	    
	    foreach ($this->_branches as $branch)
	    {
	        $this->_import_branch($branch);
	    }
         
        $this->_prune_queue();
	}
	
	/**
	 * Fetches branch names from the kohana-modules repository
	 * and stores them in an instance variable.
	 */
	private function _fetch_branches()
	{
		$results = Github::instance()
            ->getRepoApi()
            ->getRepoBranches('ahutchings', 'kohana-modules');
        
        $this->_branches = array_keys($results);
	}
	
	/**
	 * Creates a kohana_version for each branch in the kohana-modules repo.
	 */
	private function _create_kohana_versions()
	{
	    foreach ($this->_branches as $branch)
	    {
            $count = DB::select('COUNT("*") AS mycount')
                ->from('kohana_versions')
                ->where('name', '=', $branch)
                ->execute()
                ->get('mycount');

            if ($count == 0)
            {
                $query = DB::query(Database::INSERT, 'INSERT INTO kohana_versions (name) VALUES (:branch)')
                    ->param(':branch', $branch)
                    ->execute();
            }
	    }
	}
	
	private function _import_branch($branch)
	{
	    $version_id = DB::select('id')
	        ->from('kohana_versions')
	        ->where('name', '=', $branch)
	        ->execute()
	        ->get('id');

	    $matches = $this->_fetch_gitmodules("https://github.com/ahutchings/kohana-modules/raw/$branch/.gitmodules");

        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $module = ORM::factory('module')
                ->where('username', '=', $matches['username'][$i])
                ->where('name', '=', $matches['name'][$i])
                ->find();

            if ( ! $module->loaded())
            {

                $module->username = $matches['username'][$i];
                $module->name     = $matches['name'][$i];
                $module->save();

                $module->refresh_github_metadata();

                // throttle API requests
                sleep(2);
            }
            
            // Create a compatibility entry for the version
            $count = DB::select('COUNT("*") AS mycount')
                ->from('module_compatibilities')
                ->where('module_id', '=', $module->id)
                ->where('kohana_version_id', '=', $version_id)
                ->execute()
                ->get('mycount');
                
            if ($count == 0)
            {
                DB::query(Database::INSERT,
                    "INSERT INTO module_compatibilities (module_id, kohana_version_id)
                    VALUES (:module_id, :version_id)")
                    ->param(':module_id', $module->id)
                    ->param(':version_id', $version_id)
                    ->execute();
            }
        }
	}
	
	private function _fetch_gitmodules($url)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        return $matches;
    }
    
    /**
     * Delete search results that have been added to the module index.
     */
    private function _prune_queue()
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
