<?php defined('SYSPATH') or die('No direct script access.');

class Model_Queue extends ORM
{
    protected $_table_name = 'queue';

    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);

    protected $_sorting = array('created_at' => 'ASC');

    public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                ),
            'username' => array(
                array('not_empty'),
                array(array($this, 'not_in_modules'), array(':validation', ':field')),
                array(array($this, 'not_in_queue'), array(':validation', ':field')),
                ),
            'source' => array(
                array('not_empty'),
                array('in_array', array(':value', array(self::SOURCE_GITHUB_SEARCH, self::SOURCE_KOHANA_UNIVERSE))),
                ),
            'description' => array(),
            'is_ignored'  => array(),
            );
    }

    public function filters()
    {
        return array(
            TRUE => array(
                array('trim'),
                ),
            );
    }

    const SOURCE_GITHUB_SEARCH   = 'github_search';
    const SOURCE_KOHANA_UNIVERSE = 'kohana_universe';

    /**
     * Makes sure the username/name combo is not in the modules table.
     */
    public function not_in_modules(Validation $data, $field)
    {
        $count = ORM::factory('module')
            ->where('username', '=', $data['username'])
            ->where('name', '=', $data['name'])
            ->count_all();

        if ($count > 0)
        {
            $data->error($field, 'exists_in_modules');
        }
    }

    /**
     * Makes sure the username/name combo is not in the queue table.
     */
    public function not_in_queue(Validation $data, $field)
    {
        $query = ORM::factory('queue')
            ->where('username', '=', $data['username'])
            ->where('name', '=', $data['name']);
            
        if ($this->loaded())
        {
            $query->where('id', '!=', $this->id);
        }
            
        $count = $query->count_all();
        
        if ($count > 0)
        {
            $data->error($field, 'exists_in_queue');
        }
    }
}
