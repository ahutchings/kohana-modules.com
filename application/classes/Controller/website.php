<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Website extends Controller_Template
{
    public function before()
    {
        parent::before();
        
        if ($this->auto_render)
        {
            $this->template->tagline = __('Indexing <span>:modules</span> modules from <span>:developers</span> developers.',
                array(
                    ':modules' => ORM::factory('module')->count_all(),
                    ':developers' => DB::query(Database::SELECT, 'SELECT DISTINCT username FROM modules')->execute()->count()
                    )
                );
            
            // Set a default meta description
            $this->template->meta_description = strip_tags($this->template->tagline);
        }
    }
}
