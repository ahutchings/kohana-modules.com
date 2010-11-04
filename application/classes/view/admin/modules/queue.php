<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Modules_Queue extends View_Layout_Admin
{
    public $title = 'Approval Queue | Modules | ';
    
    public function search_results()
    {
        return ORM::factory('searchresult')
            ->find_all()
            ->as_array();
    }
}
