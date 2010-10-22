<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller
{
    public function before()
    {
        parent::before();

        if (!Auth::instance()->logged_in('admin'))
        {
            $this->request->redirect(url::site('login'));
        }
    }
}
