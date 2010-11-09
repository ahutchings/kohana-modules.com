<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Website extends Controller_Template
{
    public function action_index()
    {
        $this->template->title = '';
        $this->template->content = View::factory('website/index')
            ->bind('modules', $modules)
            ->bind('pagination', $pagination);

        $count = DB::select(DB::expr('COUNT(*) AS count'))
            ->from('modules')
            ->execute()
            ->get('count');

        $pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $modules = ORM::factory('module')
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();
    }
}
