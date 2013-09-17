<?php defined('SYSPATH') or die('No direct script access.');

class Model_Module_Ref extends ORM
{
	protected $_table_name = 'module_commits';
    protected $_belongs_to = array
    (
        'module'            => array(),
    );

    public function rules()
    {
        return array(
            'sha' => array(
                array('not_empty'),
                ),
            'version' => array(
                array('not_empty'),
	            array('digit')
                ),
            );
    }
}
