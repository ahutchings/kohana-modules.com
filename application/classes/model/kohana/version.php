<?php defined('SYSPATH') or die('No direct script access.');

class Model_Kohana_Version extends ORM
{
    protected $_has_many = array(
        'modules' => array(
            'through' => 'module_compatibilities',
        ),
    );
    
    /**
     * Returns a sorted non-associative array of Kohana versions.
     *
     * @return  array
     */
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
