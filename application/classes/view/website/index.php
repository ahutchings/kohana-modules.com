<?php defined('SYSPATH') or die('No direct script access.');

class View_Website_Index extends View_Layout
{
    public $title = ' :: Punchy tagline here!';

    public function modules()
    {
        $modules = array();

        foreach (ORM::factory('module')->find_all() as $module)
        {
            $modules[] = $module->as_array();
        }

        return $modules;
    }
}
