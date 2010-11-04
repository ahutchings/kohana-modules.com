<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Dashboard extends View_Layout_Admin
{
    protected $_pragmas = array(Kostache::PRAGMA_DOT_NOTATION => TRUE);
    
    public $title = 'Dashboard | ';
    
    public function open_tickets()
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
    
    public function search_results()
    {
        return ORM::factory('searchresult')->count_all();
    }
    
    public function pending_deletion()
    {
        return ORM::factory('module')
            ->where('flagged_for_deletion_at', 'IS NOT', NULL)
            ->count_all();
    }
    
    public function next_cron()
    {
        return Date::span(Cron_Helper::next_run(), NULL, 'hours,minutes,seconds');
    }

    public function recently_updated()
    {
        $modules_obj = ORM::factory('module')
            ->order_by('updated_at', 'DESC')
            ->limit(5)
            ->find_all();
        
        $modules = array();
        
        foreach ($modules_obj as $module)
        {
            $module = $module->as_array();
            $module['updated_at'] = Date::fuzzy_span($module['updated_at']);
            
            $modules[] = $module;
        }
        
        return $modules;
    }
    
    public function newest_modules()
    {
        $modules_obj = ORM::factory('module')
            ->order_by('created_at', 'DESC')
            ->limit(5)
            ->find_all()
            ->as_array();
            
        $modules = array();
        
        foreach ($modules_obj as $module)
        {
            $module = $module->as_array();
            $module['created_at'] = Date::fuzzy_span($module['created_at']);
            
            $modules[] = $module;
        }
        
        return $modules;
    }
}
