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
    
    public function action_queue()
    {
        echo new View_Admin_Modules_Queue;
    }

    public function action_ignored()
    {
        echo new View_Admin_Modules_Ignored;
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
    
    public function action_ignore()
    {
        if (isset($_GET['username']) AND isset($_GET['name']))
        {
            $repo = Github::instance()->getRepoApi()->show($_GET['username'], $_GET['name']);
            
            $ignored = ORM::factory('ignored');
            $ignored->values($repo);
            $ignored->username = isset($repo['owner']) ? $repo['owner'] : $repo['username'];
            $ignored->save();
            
            if (isset($_GET['from']) && $_GET['from'] == 'search')
            {
                $searchresult = ORM::factory('searchresult')
                    ->where('username', '=', $_GET['username'])
                    ->where('name', '=', $_GET['name'])
                    ->find()
                    ->delete();   
            }
            
            Notices::add('success', "Ignored repository $ignored->username/$ignored->name");
        }
    
        $this->request->redirect('admin/modules/queue');
    }
}
