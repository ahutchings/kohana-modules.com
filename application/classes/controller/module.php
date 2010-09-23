<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Module extends Controller
{
    public function action_show($user, $name)
    {
        echo new View_Module_Show($user, $name);
    }
}
