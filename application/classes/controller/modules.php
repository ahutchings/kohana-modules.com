<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller_Website
{
    public function action_show($username, $name)
    {
        $module = ORM::factory('module')
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find();

        if ( ! $module->loaded())
        {
            throw new HTTP_Exception_404('Module :username/:name not found',
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
        $query = ORM::factory('module')
            ->where('username', '=', $username);

        $count = $query->reset(FALSE)->count_all();

        if ($count == 0)
        {
            throw new HTTP_Exception_404('No modules found for :username',
                array(':username' => $username));
        }

        $default_version = 'any';

        $compatibility = Arr::get($_GET, 'compatibility', $default_version);

        if ($compatibility !== 'any')
        {
            $query->where_compatible_with($compatibility);

            // Perform another count for the active filter
            $count = $query->reset(FALSE)->count_all();
        }

        $this->template->title = "$username's Profile - ";
        $this->template->content = View::factory('modules/by_username')
            ->bind('count', $count)
            ->set('username', $username)
            ->bind('modules', $modules)
            ->bind('pagination', $pagination)
            ->bind('default_version', $default_version)
            ->bind('versions', $versions);

        $versions = ORM::factory('kohana_version')
                ->order_by('name', 'DESC')
                ->find_all();

        $pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $modules = $query
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
            ->bind('pagination', $pagination)
            ->bind('default_version', $default_version)
            ->bind('versions', $versions);

        $default_version = Model_Kohana_Version::latest();

        $versions = ORM::factory('kohana_version')
                ->order_by('name', 'DESC')
                ->find_all();

        $query = ORM::factory('module');

        $compatibility = Arr::get($_GET, 'compatibility', $default_version);

        if ($compatibility !== 'any')
        {
            $query->where_compatible_with($compatibility);
        }

        $pagination = Pagination::factory(array(
            'total_items' => $query->reset(FALSE)->count_all(),
            ));

        $modules = $query
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->set_order_by()
            ->find_all();
    }
}
