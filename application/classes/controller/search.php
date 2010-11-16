<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template
{
    public function action_index()
    {
        $this->template->title   = "Search for \"{$_GET['query']}\" | ";
        $this->template->content = View::factory('search/index')
            ->bind('exact', $exact)
            ->bind('fuzzy', $fuzzy);

        $module = ORM::factory('module')
            ->where('name', '=', $_GET['query'])
            ->find();

        $exact = $module->loaded() ? $module : FALSE;

        $fuzzy = ORM::factory('module')
            ->where('name', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->or_where('description', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->find_all();
    }
}
