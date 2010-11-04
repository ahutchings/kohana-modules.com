<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Modules_Ignored extends View_Layout_Admin
{
    public $title = 'Ignored | Modules | ';
    
    public function modules()
    {
        return ORM::factory('ignored')
            ->find_all()
            ->as_array();
    }
}
