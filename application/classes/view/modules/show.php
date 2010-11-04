<?php defined('SYSPATH') or die('No direct script access.');

class View_Modules_Show extends View_Layout
{
    protected $_pragmas = array(Kostache::PRAGMA_DOT_NOTATION => TRUE);
    
    public $module;
    public $repository;
    public $tags;

    public function __construct($username, $name)
    {
        $this->module = ORM::factory('module')
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find()
            ->as_array();
        
        $this->repository = Github::instance()
            ->getRepoApi()
            ->show($username, $name);
            
        
        $tags = Github::instance()->getRepoApi()->getRepoTags($username, $name);
        
        foreach ($tags as $k => $v)
        {
            $this->tags[] = array
            (
                'name' => $k,
                'hash' => $v,
                'url'  => "http://github.com/$username/$name/tree/$k",
            );
        }
    }
    
    public function title()
    {
        return $this->module['username'].'/'.$this->module['name'].' | ';
    }
}
