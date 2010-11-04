<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller
{
    public function action_show($user, $name)
    {
        echo new View_Modules_Show($user, $name);
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
