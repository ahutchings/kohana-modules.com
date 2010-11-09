<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Template
{
    public function action_display($page)
    {
        $titles = array
        (
            'about'    => 'About',
            'feedback' => 'Feedback',
            'login'    => 'Login',
            'suggest'  => 'Suggest a module',
        );
        
        $this->template->title = $titles[$page].' | ';
        $this->template->content = View::factory("pages/$page");
    }
}
