<?php defined('SYSPATH') or die('No direct script access.');

class View_Website_Index extends View_Layout
{
    public $title = '';
    
    public $pagination;

    public function modules()
    {
        $modules = array();

        $count = DB::select(DB::expr('COUNT(*) AS count'))
            ->from('modules')
            ->execute()
            ->get('count');

        $this->pagination = Pagination::factory(array(
            'total_items' => $count,
            ));

        $objects = ORM::factory('module')
            ->limit($this->pagination->items_per_page)
            ->offset($this->pagination->offset)
            ->find_all();

        foreach ($objects as $module)
        {
            $modules[] = $module->as_array();
        }

        return $modules;
    }
    
    public function pagination()
    {
        return $this->pagination->render();
    }
}
