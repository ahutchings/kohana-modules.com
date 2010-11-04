<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller
{
    public function action_index()
    {
        echo new View_Search_Index($_GET['query']);
    }
}
