<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'file' => DOCROOT.'satis.json',
	'output_dir' => DOCROOT,
	'require-all' => true,
	'command_path' => APPPATH.'vendor'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'satis'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'satis',
	'command' => 'php :satis build :satis_json :output_dir'
);
