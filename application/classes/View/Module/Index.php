<?php defined('SYSPATH') or die('No direct script access.');

class View_Module_Index extends View_Layout
{
    public $title = '';
    private $query;

    public function __construct($query)
    {
        $this->query      = $query;
        $this->pagination = $this->getPagination();
    }

    private function getPagination()
    {
        return Pagination::factory(array(
            'total_items' => $this->count()
            ));
    }

    public function count()
    {
        return $this->query->reset(FALSE)->count_all();
    }

    public function modules()
    {
        $modules = $this->getModules();

        $formattedModules = array_map(array($this, 'formatModule'), $modules);

        return $formattedModules;
    }

    private function getModules()
    {
        return $this->query
            ->limit($this->pagination->items_per_page)
            ->offset($this->pagination->offset)
            ->find_all()
            ->as_array();
    }

    protected function formatModule($module)
    {
        return array(
            'name'        => $module->name,
            'username'    => $module->username,
            'identifier'  => $this->formatModuleIdentifier($module),
            'description' => $this->formatModuleDescription($module),
	        'watchers'    => $module->watchers,
	        'stars'       => $module->stars,
            'forks'       => $module->forks
            );
    }

    protected function formatModuleIdentifier($module)
    {
        return $module->username.'/'.$module->name;
    }

    protected function formatModuleDescription($module)
    {
        return Text::widont($module->description);
    }

    public function pagination()
    {
        return $this->pagination->render();
    }

    public function sorter()
    {
        $default_version = Model_Kohana_Version::latest();
        $view            = new View_Module_Sort($default_version);

        return Kostache::factory()->render($view);
    }
}
