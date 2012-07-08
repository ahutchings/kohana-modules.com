<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Website
{
    public function action_index()
    {
        $term = $_GET['query'];

        $this->template->title   = "Search: $term - ";
        $this->template->content = View::factory('search/index')
            ->bind('pagination', $pagination)
            ->bind('modules', $modules)
            ->bind('versions', $versions);

        $query = ORM::factory('module')
            ->where_open()
                ->where('name', 'LIKE', "%$term%")
                ->or_where('description', 'LIKE', "%$term%")
                ->or_where('username', 'LIKE', "%$term%")
            ->where_close();

        if (isset($_GET['compatibility']))
        {
            $query->where_compatible_with($_GET['compatibility']);
        }

        $pagination = Pagination::factory(array(
            'total_items' => $query->reset(FALSE)->count_all(),
            ));


        $versions = ORM::factory('kohana_version')
                ->order_by('name', 'DESC')
                ->find_all();

        $modules = $query
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->set_order_by()
            ->find_all();
    }
}
