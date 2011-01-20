<?php defined('SYSPATH') or die('No direct script access.');

class Model_Queue extends ORM
{
    protected $_table_name = 'queue';

    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);

    protected $_sorting = array('created_at' => 'ASC');

    protected $_rules = array
    (
        'name'        => array('not_empty' => array()),
        'username'    => array('not_empty' => array()),
        'source'      => array(
            'not_empty' => array(),
            'in_array'  => array(array(self::SOURCE_GITHUB_SEARCH, self::SOURCE_KOHANA_UNIVERSE)),
            ),
        'description' => array(),
        'is_ignored'  => array(),
    );

    protected $_callbacks = array
    (
        'username' => array('not_in_modules', 'not_in_queue'),
    );

    protected $_filters = array
    (
        TRUE => array('trim' => array()),
    );

    const SOURCE_GITHUB_SEARCH   = 'github_search';
    const SOURCE_KOHANA_UNIVERSE = 'kohana_universe';

    /**
     * Makes sure the username/name combo is not in the modules table.
     */
    public function not_in_modules(Validate $data, $field)
    {
        $count = ORM::factory('module')
            ->where('username', '=', $data['username'])
            ->where('name', '=', $data['name'])
            ->count_all();
            
        if ($count > 0)
        {
            $data->error($field, 'exists_in_modules', array($data[$field]));
        }
    }

    /**
     * Makes sure the username/name combo is not in the queue table.
     */
    public function not_in_queue(Validate $data, $field)
    {
        $count = ORM::factory('queue')
            ->where('username', '=', $data['username'])
            ->where('name', '=', $data['name'])
            ->count_all();
        
        if ($count > 0)
        {
            $data->error($field, 'exists_in_queue', array($data[$field]));
        }
    }
}
