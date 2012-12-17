<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Queue extends Controller_Admin
{
    public function action_index()
    {
        $this->template->title = 'Approval Queue - Modules - ';
        $this->template->content = View::factory('admin/modules/queue')
            ->bind('search', $search)
            ->bind('universe', $universe);

        $search = ORM::factory('Queue')
            ->where('is_ignored', '=', FALSE)
            ->where('source', '=', Model_Queue::SOURCE_GITHUB_SEARCH)
            ->find_all();

        $universe = ORM::factory('Queue')
            ->where('is_ignored', '=', FALSE)
            ->where('source', '=', Model_Queue::SOURCE_KOHANA_UNIVERSE)
            ->find_all();
    }

    public function action_ignored()
    {
        $this->template->title = 'Ignored - Modules - ';
        $this->template->content = View::factory('admin/modules/ignored')
            ->bind('ignored', $ignored);

        $ignored = ORM::factory('Queue')->where('is_ignored', '=', TRUE)->find_all();
    }

    public function action_ignore($id)
    {
        $queue = ORM::factory('Queue', $id);
        $queue->is_ignored = TRUE;
        $queue->save();

        Notices::add('success', "Ignored repository $queue->username/$queue->name");
        $this->request->redirect('admin/queue');
    }
}
