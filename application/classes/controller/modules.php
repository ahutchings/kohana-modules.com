<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller_Template
{
    public function action_show($username, $name)
    {
        $module = ORM::factory('module')
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find();
            
        if ( ! $module->loaded())
        {
            throw new Kohana_Request_Exception('Module :username/:name not found',
                array(':username' => $username, ':name' => $name));
        }
        
        $this->template->title   = "$module->username/$module->name - ";
        $this->template->content = View::factory('modules/show')
            ->bind('module', $module);
            
        if (Valid::not_empty($module->description))
        {
            $this->template->meta_description = $module->description;
        }
    }

    public function action_by_username($username)
    {
        $count = DB::select(DB::expr('COUNT(*) AS count'))
            ->from('modules')
            ->where('username', '=', $username)
            ->execute()
            ->get('count');
            
        if ($count == 0)
        {
            throw new Kohana_Request_Exception('No modules found for :username',
                array(':username' => $username));
        }
        
        $this->template->title = "$username's Profile - ";
        $this->template->content = View::factory('modules/by_username')
            ->bind('count', $count)
            ->set('username', $username)
            ->bind('modules', $modules)
            ->bind('pagination', $pagination);

        $pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $modules = ORM::factory('module')
            ->where('username', '=', $username)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->set_order_by()
            ->find_all();
    }
    
    public function action_index()
    {
        $this->template->title = '';
        $this->template->content = View::factory('modules/index')
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
            ->set_order_by()
            ->find_all();
    }
}
