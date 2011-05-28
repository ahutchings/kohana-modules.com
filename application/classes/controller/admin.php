<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin extends Controller_Template
{
    public $template = 'admin/template';
    
    public function before()
    {
        parent::before();

        if ( ! Auth::instance()->logged_in('admin'))
        {
            $this->request->redirect('user/login');
        }
    }
}
