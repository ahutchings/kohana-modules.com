<?php
/**
 * Refreshes local repository metadata from GitHub.
 */
class Task_Composer_Sync extends Minion_Task
{
	/**
	 * Execute the task with the specified set of config
	 *
	 * @return boolean TRUE if task executed successfully, else FALSE
	 */
	protected function _execute(array $params)
	{
		$cfg = Kohana::$config->load('satis')->as_array();

		// load all Kohana 3.3 modules
		$modules = ORM::factory('Module')
			->where('has_composer', '=', false)
			->where('flagged_for_deletion_at', '=', null)
			->find_all();

		//build the satis file
		$satis = array(
			'name' => 'Kohana-modules hosted packages',
			'homepage' => 'http://kohana-modules.com',
			'output-html' => false,
			'require-all' => $cfg['require-all'],
			'repositories' => array()
		);

		foreach($modules as $module)
		{
			$refs = $module->refs->find_all();

			foreach($refs as $ref)
			{
				$satis['repositories'][] = array(
					'type' => 'package',
					'package' => array(
						'name' => $module->package_name,
						'type' => 'kohana-module',
						'version' => '1.0.'.$ref->version,
						'source' => array(
							'url' => $module->url().'.git',
							'type' => 'git',
							'reference' => $ref->sha
						)
					)
				);
			}
		}

		//save the satis file
		file_put_contents($cfg['file'], json_encode($satis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ));

		$command = __($cfg['command'], array(
			':satis' => $cfg['command_path'],
			':satis_json' => $cfg['file'],
			':output_dir' => $cfg['output_dir']
		));
		Minion_CLI::write('Executing: '.$command);
		Minion_CLI::write(passthru($command));
	}
}
