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
    
    public function action_process_suggest()
    {
        $post = Validate::factory($_POST)
            ->filter(TRUE, 'trim')
            ->rule('github_url', 'not_empty')
            ->rule('github_url', 'url');
            
        if ($post->check())
        {
            // @todo create issue in kohana-modules repository

            Notices::add('success', "Your message has been received");
        }

        Notices::add('error', implode('<br />', $post->errors('validate')));
        
        $this->request->redirect('pages/suggest');
    }
}
