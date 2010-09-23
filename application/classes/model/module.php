<?php defined('SYSPATH') or die('No direct script access.');

class Model_Module extends ORM
{
    protected $_created_column = array('column' => 'created_at', 'format' => TRUE);
    protected $_updated_column = array('column' => 'updated_at', 'format' => TRUE);
}
