<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template
{
    public function action_index()
    {
        $this->template->title   = "Search for \"{$_GET['query']}\" - ";
        $this->template->content = View::factory('search/index')
            ->bind('pagination', $pagination)
            ->bind('modules', $modules);

        $count = DB::select(DB::expr('COUNT(*) AS count'))
            ->from('modules')
            ->where('name', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->or_where('description', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->or_where('username', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->execute()
            ->get('count');

        $pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $modules = ORM::factory('module')
            ->where('name', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->or_where('description', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->or_where('username', NULL, DB::expr("LIKE '%{$_GET['query']}%'"))
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->order_by('watchers', 'DESC')
            ->find_all();
    }
}
