<?php

class Task_Module_Discover extends Minion_Task
{
    /**
     * Execute the task with the specified set of config
     *
     * @return boolean TRUE if task executed successfully, else FALSE
     */
    protected function _execute(array $params)
    {
        $this->_import_from_search();
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
                $queue = ORM::factory('Queue');
                $queue->values($result);
                $queue->source = Model_Queue::SOURCE_GITHUB_SEARCH;

                try
                {
                   $queue->save();
                }
                catch (ORM_Validation_Exception $e) {}
            }

            // throttle API requests
            sleep(2);

            $i++;
        }
    }
}
