<?php defined('SYSPATH') or die('No direct script access.');

class View_Module_ByUsername extends View_Module_Index
{
	public function __construct($query, $username)
	{
		$this->username = $username;
		parent::__construct($query);
	}

	public function title()
	{
		return "$this->username's Profile";
	}

	public function count()
	{
		return $this->getTotalItems();
	}

	public function module_count_noun()
	{
		return Inflector::plural('Module', $this->getTotalItems());
	}
}
