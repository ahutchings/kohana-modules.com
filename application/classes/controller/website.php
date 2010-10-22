<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Website extends Controller
{
    public function action_index()
    {
        echo new View_Website_Index;
    }
}
