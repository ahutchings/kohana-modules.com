<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller
{
    public function action_display($page)
    {
        $class = 'View_Pages_'.ucwords($page);
        
        echo new $class;
    }
}
