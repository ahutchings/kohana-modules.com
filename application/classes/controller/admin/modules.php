<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules extends Controller_Admin
{
    public function action_index()
    {
        echo new View_Admin_Modules_Index;
    }
    
    public function action_queue()
    {
        echo new View_Admin_Modules_Queue;
    }
    
    public function action_pending_deletion()
    {
        echo new View_Admin_Modules_PendingDeletion;
    }
    
    public function action_add()
    {
        if (isset($_GET['username']) AND isset($_GET['name']))
        {
            $repo = Github::instance()->getRepoApi()->show($_GET['username'], $_GET['name']);

            $module = ORM::factory('module');
            $module->username = isset($repo['owner']) ? $repo['owner'] : $repo['username'];
            $module->name = $repo['name'];
            $module->description = $repo['description'];
            $module->save();

            Notices::add('success', "Added module $module->username/$module->name");
            
            $this->request->redirect('admin/modules');
        }
    }
}
