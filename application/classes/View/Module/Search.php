<?php defined('SYSPATH') or die('No direct script access.');

class View_Module_Search extends View_Module_Index
{
    private $term;

    public function __construct($query, $term)
    {
        $this->term = $term;
        parent::__construct($query);
    }

    public function title()
    {
        return 'Search: '.$this->term;
    }

    protected function formatModuleIdentifier($module)
    {
        $formatted = parent::formatModuleIdentifier($module);
        return $this->highlight($formatted);
    }

    protected function formatModuleDescription($module)
    {
        $formatted = parent::formatModuleDescription($module);
        return $this->highlight($formatted);
    }

    private function highlight($str)
    {
        return Text::highlight($str, $this->term);
    }
}
