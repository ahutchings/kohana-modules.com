<?php defined('SYSPATH') or die('No direct script access.');

class View_Modules_Show extends View_Layout
{
    public $module;

    public function __construct($name)
    {
        $this->module = ORM::factory('module')
            ->where('name', '=', $name)
            ->find()
            ->as_array();
    }
    
    public function title()
    {
        return $this->module['name'].' | ';
    }
}
