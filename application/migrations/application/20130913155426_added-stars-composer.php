<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * added-stars-composer
 */
class Migration_Application_20130913155426 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, 'ALTER TABLE  `modules` ADD  `stars` INT UNSIGNED NOT NULL , ADD  `has_composer` BOOLEAN NOT NULL');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE  `modules` DROP COLUMN `stars`, DROP COLUMN `has_composer`');
	}

}
