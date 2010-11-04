<?php defined('SYSPATH') or die('No direct script access.');

class View_Modules_Show extends View_Layout
{
    protected $_pragmas = array(Kostache::PRAGMA_DOT_NOTATION => TRUE);
    
    public $module;
    public $tags;

    public function __construct($username, $name)
    {
        $this->module = ORM::factory('module')
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find()
            ->as_array();
    }
    
    public function title()
    {
        return $this->module['username'].'/'.$this->module['name'].' | ';
    }
}
