<?php defined('SYSPATH') or die('No direct script access.');

class View_Error_500 extends View_Error
{
    public $code = 500;

    public function message()
    {
        return 'Oops, there was a problem.';
    }
}
