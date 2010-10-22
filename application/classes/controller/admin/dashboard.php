<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin
{
    public function action_index()
    {
        echo new View_Admin_Dashboard;
    }
}
