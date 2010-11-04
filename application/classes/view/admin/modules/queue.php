<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Modules_Queue extends View_Layout_Admin
{
    public $title = 'Approval Queue | Modules | ';
    
    public function search_results()
    {
        $repositories = Github::instance()
            ->getRepoApi()
            ->search('kohana');
        
        $modules = DB::select('username', 'name')
            ->from('modules')
            ->execute();
        
        return self::filter_existing($repositories, $modules);
    }
    
    public static function filter_existing($repositories, $modules)
    {
        $repositories_nonassoc = array();
        $modules_nonassoc = array();
        
        foreach ($repositories as $repository)
        {
            $repositories_nonassoc[$repository['username'].'/'.$repository['name']] = $repository['description'];
        }
        
        foreach ($modules as $module)
        {
            $modules_nonassoc[$module['username'].'/'.$module['name']] = '';
        }
        
        $filtered = array_diff_key($repositories_nonassoc, $modules_nonassoc);
        
        return self::reassoc($filtered);
    }
    
    public static function reassoc($array)
    {
        $return = array();
        
        foreach ($array as $k => $description)
        {
            list($username, $name) = explode('/', $k);
            
            $return[] = array
            (
                'username'    => $username,
                'name'        => $name,
                'description' => $description,
            );
        }
        
        return $return;
    }
}
