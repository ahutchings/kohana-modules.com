<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Controller_Cron extends Controller
{
    /**
	 * @var  integer  time the request was made
	 */
    protected $_start_time;

    /**
	 * @var  integer  time the request was completed
	 */
    protected $_end_time;
    
    public function before()
    {
        parent::before();

        if (Kohana::$environment === Kohana::PRODUCTION AND ! Kohana::$is_cli)
        {
            throw new Kohana_Request_Exception('Attempt to access cron outside of command line environment',
                NULL, 403);
        }

        Kohana_Log::instance()->add('CRON', 'Starting cronjob: :job',
            array(':job' => Request::instance()->uri));
            
        $this->_start_time = time();
    }
    
    public function after()
    {
        $this->_end_time = time();
        
        parent::after();
        
        Kohana_Log::instance()->add('CRON', 'Completed cronjob: :job in :seconds seconds',
            array(':job' => Request::instance()->uri, ':seconds' => ($this->_end_time - $this->_start_time)));
    }
}
