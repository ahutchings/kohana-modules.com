<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Kohana_Controller_Cron
{
    /**
     * Imports new repositories from the master module repository.
     */
    public function action_import_from_master()
    {
        Job::import_from_master();
    }

    /**
     * Imports new repositories from kolanos/kohana-universe.
     */
    public function action_import_from_universe()
    {
        Job::import_from_universe();
    }
    
    /**
     * Flags modules that have been removed from GitHub.
     */
    public function action_flag_deleted()
    {
        Job::flag_deleted();
    }
    
    /**
     * Refreshes local repository metadata from GitHub.
     */
    public function action_refresh_metadata()
    {
        Job::refresh_metadata();
    }
    
    /**
     * Fetches search results from GitHub and stores them locally.
     */
    public function action_import_from_search()
    {
        Job::import_from_search();
    }
    
    /**
     * Delete search results that have been added to the module index.
     */
    public function action_prune_queue()
    {
        Job::prune_queue();
    }
}
