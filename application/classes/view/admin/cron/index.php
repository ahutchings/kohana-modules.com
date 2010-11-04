<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Cron_Index extends View_Layout_Admin
{
    protected $_pragmas = array(Kostache::PRAGMA_DOT_NOTATION => TRUE);
    
    public $title = 'Cron | ';
    
    public function jobs()
    {
        return Cron_Helper::get_jobs();
    }
}
