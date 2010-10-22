<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller
{
    public function action_logout()
    {
        Auth::instance()->logout(TRUE);

        $this->request->redirect(url::site());
    }
    
    public function action_login()
    {
        echo new View_User_Login();
    }

    public function action_process_login()
    {
        $auth = Auth::instance();

        if ($auth->login($_POST['username'], $_POST['password']))
        {
            $this->request->redirect(url::base());
        }
        else
        {
            Notices::add('error', 'Incorrect username or password.');
            $this->request->redirect('user/login');
        }
    }
}
