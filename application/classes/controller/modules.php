<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller_Template
{
    public function action_show($username, $name)
    {
        $module = ORM::factory('module')
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find();
        
        $this->template->title   = $module->username.'/'.$module->name.' | ';
        $this->template->content = View::factory('modules/show')
            ->bind('module', $module);
    }

    public function action_by_username($username)
    {
        $this->template->title = $username.' | ';
        $this->template->content = View::factory('modules/by_username')
            ->bind('count', $count)
            ->set('username', $username)
            ->bind('modules', $modules)
            ->bind('pagination', $pagination);

        $count = DB::select(DB::expr('COUNT(*) AS count'))
            ->from('modules')
            ->where('username', '=', $username)
            ->execute()
            ->get('count');

        $pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $modules = ORM::factory('module')
            ->where('username', '=', $username)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->order_by('watchers', 'DESC')
            ->find_all();
    }
}
