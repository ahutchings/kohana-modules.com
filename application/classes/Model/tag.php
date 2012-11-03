<?php defined('SYSPATH') or die('No direct script access.');

class Model_Tag extends ORM
{
    protected $_sorting = array('name' => 'ASC');
    
    protected $_belongs_to = array
    (
        'module' => array(),
    );
}
