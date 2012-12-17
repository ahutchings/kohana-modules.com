<?php

class Task_User_Create extends Minion_Task
{
	protected $_options = array(
		'username' => NULL,
		'password' => NULL,
		'email'    => NULL
	);

	protected function _execute(array $params)
	{
		try
		{
			$params['password_confirm'] = $params['password'];

			$user = ORM::factory('User')
				->create_user($params, array_keys($this->_options));

			Minion_CLI::write('User created: '.$params['username']);
		}
		catch (ORM_Validation_Exception $e)
		{
			$errors = $e->errors();
			foreach ($errors as $key => $values)
			{
				Minion_CLI::write(Minion_CLI::color($key.' failed check: '.$values[0], 'red'));
			}
		}
	}
}
