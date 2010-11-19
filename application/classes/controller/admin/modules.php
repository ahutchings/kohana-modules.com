<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules extends Controller_Admin
{
    public function action_index()
    {
        $this->template->title   = 'Modules | ';
        $this->template->content = View::factory('admin/modules/index')
            ->bind('modules', $modules);

        $modules = ORM::factory('module')->find_all();
    }
    
    public function action_pending_deletion()
    {
        $this->template->title = 'Pending Deletion | Modules | ';
        $this->template->content = View::factory('admin/modules/pendingdeletion')
            ->bind('modules', $modules);

        $modules = ORM::factory('module')
            ->where('flagged_for_deletion_at', 'IS NOT', NULL)
            ->find_all();
    }
}
