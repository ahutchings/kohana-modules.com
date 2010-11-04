<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Suggest extends View_Layout
{
    public $title = 'Suggest a module | ';
    
    public function form()
    {
        $yf = YForm::factory('suggest');

        return array
        (
            'open'       => $yf->open('/modules/process_suggest'),
            'github_url' => $yf->text('github_url'),
            'submit'     => $yf->submit('Submit'),
            'close'      => $yf->close(),
        );
    }
}
