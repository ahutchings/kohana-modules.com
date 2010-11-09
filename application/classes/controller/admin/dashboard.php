<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin
{
    public function action_index()
    {
        $this->template->title = 'Dashboard | ';
        $this->template->content = View::factory('admin/dashboard')
            ->set('open_tickets', $this->_open_tickets())
            ->set('next_cron', $this->_next_cron())
            ->bind('newest', $newest)
            ->bind('recently_updated', $recently_updated);

        $newest = ORM::factory('module')
            ->order_by('created_at', 'DESC')
            ->limit(5)
            ->find_all();    

        $recently_updated = ORM::factory('module')
            ->order_by('updated_at', 'DESC')
            ->limit(5)
            ->find_all();
    }
    
    private function _open_tickets()
    {
        $open_tickets = Kohana::cache('open_tickets');

        if ($open_tickets !== NULL)
        {
            return $open_tickets;
        }

        $repo = Github::instance()->getRepoApi()->show('ahutchings', 'kohana-modules');
        Kohana::cache('open_tickets', $repo['open_issues']);

        return $repo['open_issues'];
    }

    private function _next_cron()
    {
        $next = Date::span(Cron_Helper::next_run(), NULL, 'hours,minutes,seconds');
        
        return $next['hours'].' hours, '.$next['minutes'].' minutes, '.$next['seconds'].' seconds';
    }
}
