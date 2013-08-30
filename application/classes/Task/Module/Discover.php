<?php

class Task_Module_Discover extends Minion_Task
{
    /**
     * Execute the task with the specified set of config
     *
     * @param array $params Task params
     *
     * @return boolean TRUE if task executed successfully, else FALSE
     */
    protected function _execute(array $params)
    {
        $this->importFromSearch();
    }

    const PER_PAGE = 100; // GitHub will return up to 100 results per page
    const MAX_PAGES = 10; // GitHub will return up to 1,000 results
    const SEARCH_TERM = 'kohana';

    /**
     * Fetches search results from GitHub and stores them locally.
     *
     * @return void
     */
    protected function importFromSearch()
    {
        $api = $this->_getSearchAPI();

        for ($i = 1; $i <= self::MAX_PAGES; $i++) {
            Minion_CLI::write("Searching page $i...");

            $response = $api->find(self::SEARCH_TERM, array('start_page' => $i));
            $results  = $response['repositories'];
            $this->_saveResults($results);

            if (count($results) < self::PER_PAGE) {
                break;
            }
        }

        Minion_CLI::write('Finished.');
    }

    private function _getSearchAPI()
    {
        $api = AuthenticatedGithubClient::instance()->api('repo');
        $api->setPerPage(self::PER_PAGE);
        return $api;
    }

    private function _saveResults($results)
    {
        foreach ($results as $result) {
            $this->_saveResult($result);
        }
    }

    private function _saveResult($result)
    {
        $queue = ORM::factory('Queue');
        $queue->values($result);
        $queue->source = Model_Queue::SOURCE_GITHUB_SEARCH;

        try {
            $queue->save();
        } catch (ORM_Validation_Exception $e) {
            Minion_CLI::write(Minion_CLI::color($e, 'red'));
            Minion_CLI::write(print_r($e->errors(), true));
            Minion_CLI::write(print_r($result, true));
        }
    }
}
