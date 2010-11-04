<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Modules_Index extends View_Layout_Admin
{
    public $title = 'Modules | ';
    
    public function modules()
    {
        return ORM::factory('module')
            ->find_all()
            ->as_array();
    }
}
