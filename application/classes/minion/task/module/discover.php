<?php

class Minion_Task_Module_Discover extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	public function execute(array $config)
	{
        $this->_import_from_search();
        $this->_import_from_universe();
	}
	
	/**
     * Fetches search results from GitHub and stores them locally.
     */
    protected function _import_from_search()
    {
		$i = 1;
		
		while (TRUE)
		{
			Minion_CLI::write("Searching page $i...");

			$results = Github::instance()
                ->getRepoApi()
                ->search('kohana', '', $i);

			if (count($results) === 0)
			{
				Minion_CLI::write('Finished.');
				break;
			}

            foreach ($results as $result)
            {
                $queue = ORM::factory('queue');
                $queue->values($result);
                $queue->source = Model_Queue::SOURCE_GITHUB_SEARCH;

                if ($queue->check())
                {
                    $queue->save();   
                }
            }
            
            // throttle API requests
            sleep(2);
			
			$i++;
		}
    }
    
    /**
     * Imports new repositories from kolanos/kohana-universe.
     */
    protected function _import_from_universe()
    {
        $matches = self::fetch_gitmodules("https://github.com/kolanos/kohana-universe/raw/master/.gitmodules");
        
        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $queue = ORM::factory('queue');
            $queue->username = $matches['username'][$i];
            $queue->name     = $matches['name'][$i];
            $queue->source   = Model_Queue::SOURCE_KOHANA_UNIVERSE;
            
            if ($queue->check())
            {
                $queue->save();
            }
        }
    }
    
    public static function fetch_gitmodules($url)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";

        $data = Remote::get($url);
        
        preg_match_all($pattern, $data, $matches);
        
        return $matches;
    }
}
