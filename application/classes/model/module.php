<?php defined('SYSPATH') or die('No direct script access.');

class Model_Module extends ORM
{
    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);
    
    protected $_sorting = array('name' => 'ASC');
    
    protected $_rules = array
    (
        'name' => array('not_empty' => array()),
        'user' => array('not_empty' => array()),
    );
    
    protected $_filters = array
    (
        TRUE => array('trim' => array()),
    );
}
