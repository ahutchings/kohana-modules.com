<?php defined('SYSPATH') or die('No direct script access.');

class Model_Kohana_Version extends ORM
{
    public static function names()
    {
        $names = DB::select('name')
            ->from('kohana_versions')
            ->execute()
            ->as_array(NULL, 'name');
            
        sort($names);
        
        return $names;
    }
}
