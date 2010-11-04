<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Modules_PendingDeletion extends View_Layout_Admin
{
    public $title = 'Pending Deletion | Modules | ';
    
    public function modules()
    {
        return ORM::factory('module')
            ->where('flagged_for_deletion_at', 'IS NOT', NULL)
            ->find_all()
            ->as_array();
    }
}
