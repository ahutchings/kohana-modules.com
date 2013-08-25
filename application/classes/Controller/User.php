<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller
{
    public function __construct($request, $response)
    {
        $this->renderer = Kostache_Layout::factory();

        parent::__construct($request, $response);
    }

    public function action_logout()
    {
        Auth::instance()->logout(TRUE);

        $this->redirect(url::site());
    }

    public function action_login()
    {
        $view = new View_User_Login;
        $this->response->body($this->renderer->render($view));
    }

    public function action_process_login()
    {
        $auth = Auth::instance();

        if ($auth->login($_POST['username'], $_POST['password']))
        {
            $this->redirect('admin');
        }
        else
        {
            Notices::add('error', 'Incorrect username or password.');
            $this->redirect('user/login');
        }
    }
}
