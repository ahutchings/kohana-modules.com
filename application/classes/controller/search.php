<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template
{
    public function action_index()
    {
        $term = $_GET['query'];
        
        $this->template->title   = "Search: $term - ";
        $this->template->content = View::factory('search/index')
            ->bind('pagination', $pagination)
            ->bind('modules', $modules);

        $query = ORM::factory('module')
            ->where('name', 'LIKE', "%$term%")
            ->or_where('description', 'LIKE', "%$term%")
            ->or_where('username', 'LIKE', "%$term%")
            ->order_by('watchers', 'DESC');

        $pagination = Pagination::factory(array(
            'total_items' => $query->reset(FALSE)->count_all(),
            ));
            
        $modules = $query
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();
    }
}
