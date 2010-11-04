<?php defined('SYSPATH') or die('No direct script access.');

class View_Search_Index extends View_Layout
{   
    public function __construct($query)
    {
        $this->query = $query;
    }
    
    public function title()
    {
        return "Search for \"$this->query\" | ";
    }

    public function exact()
    {
        $module = ORM::factory('module')
            ->where('name', '=', $this->query)
            ->find();

        return ($module->loaded()) ? $module->as_array() : FALSE;
    }
    
    public function fuzzy()
    {
        return ORM::factory('module')
            ->where('name', NULL, DB::expr("LIKE '%$this->query%'"))
            ->or_where('description', NULL, DB::expr("LIKE '%$this->query%'"))
            ->find_all()
            ->as_array();
    }
}
