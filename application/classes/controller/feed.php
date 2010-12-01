<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller
{
    public function before()
    {
        $this->request->headers['Content-Type'] = 'application/rss+xml; charset=utf-8';
    }
    
    public function action_index()
    {
        return $this->action_recently_added();
    }
    
    public function action_recently_added()
    {
        $items = DB::select(
                array(DB::expr('CONCAT(username,"/",name)'), 'title'),
                array(DB::expr('CONCAT("modules/",username,"/",name)'), 'link'),
                array('created_at', 'pubDate'),
                'description'
            )
            ->from('modules')
            ->order_by('created_at', 'DESC')
            ->limit(25)
            ->execute();
            
        $info = array(
            'title'       => 'Kohana Modules',
            'description' => 'Recently added modules at kohana-modules.com.',
            'pubDate'     => $items[0]['pubDate'],
            );
        
        echo Feed::create($info, $items);
    }
}
