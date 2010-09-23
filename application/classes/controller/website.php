<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Website extends Controller
{
    public function action_index()
    {
        echo new View_Website_Index;
    }
    
    public function action_page($page)
    {
        $class = 'View_Website_Page_'.ucwords($page);
        
        echo new $class;
    }
}
