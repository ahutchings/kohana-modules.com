<?php defined('SYSPATH') or die('No direct script access.');

class View_User_Login extends View_Layout
{
    public $title = 'Login | ';
    
    public function form()
    {
        $yf = YForm::factory('login');

        return array
        (
            'open'     => $yf->open('/user/process_login'),
            'username' => $yf->text('username'),
            'password' => $yf->password('password'),
            'submit'   => $yf->submit('Log in'),
            'close'    => $yf->close(),
        );
    }
}
