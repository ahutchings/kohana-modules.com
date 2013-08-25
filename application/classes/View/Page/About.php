<?php defined('SYSPATH') or die('No direct script access.');

class View_Page_About extends View_Layout
{
	protected $title = 'About';

	public $modules = array(
		array(
			'name' => 'auth',
			'url' => 'https://github.com/kohana/auth',
			'description' => 'Auth module for Kohana v3'
		),
		array(
			'name' => 'auth',
			'url' => 'https://github.com/kohana/auth',
			'description' => 'Auth module for Kohana v3'
		),
		array(
		    'name' => 'cache',
		    'url' => 'https://github.com/kohana/cache',
			'description' => 'Cache library for Kohana 3'
		),
		array(
		    'name' => 'database',
		    'url' => 'https://github.com/kohana/database',
			'description' => 'A Kohana module for database interactions, building queries, and prepared statements'
		),
		array(
		    'name' => 'github',
		    'url' => 'https://github.com/acoulton/github_v3_api',
			'description' => 'KO3 module for interacting with the new Github v3 API'
		),
		array(
		    'name' => 'minion',
		    'url' => 'https://github.com/kohana/minion',
			'description' => 'Everyone loves having a minion they can boss around'
		),
		array(
		    'name' => 'notices',
		    'url' => 'https://github.com/synapsestudios/kohana-notices',
			'description' => 'Notices module for Kohana 3.x'
		),
		array(
		    'name' => 'orm',
		    'url' => 'https://github.com/kohana/orm',
			'description' => 'Kohana ORM'
		),
		array(
		    'name' => 'pagination',
		    'url' => 'https://github.com/kohana/pagination',
			'description' => 'A Kohana module for pagination'
		),
		array(
		    'name' => 'sitemap',
		    'url' => 'https://github.com/ThePixelDeveloper/kohana-sitemap',
			'description' => 'A Kohana 3 Sitemap Class. Includes support for Google™ Mobile, Video, News, Code and Geo XML Sitemaps'
		)
	);
}
