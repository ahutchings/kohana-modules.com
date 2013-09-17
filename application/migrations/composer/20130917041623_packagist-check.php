<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * packagist check
 */
class Migration_Composer_20130917041623 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "ALTER TABLE  `modules` ADD  `package_name` VARCHAR( 140 ) NOT NULL,
			ADD  `master_branch` VARCHAR( 140 ) NOT NULL");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, "ALTER TABLE  `modules` DROP `package_name`, DROP `master_branch`;");
	}

}
