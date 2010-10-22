<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller
{
    public function action_show($user, $name)
    {
        echo new View_Modules_Show($user, $name);
    }
}
