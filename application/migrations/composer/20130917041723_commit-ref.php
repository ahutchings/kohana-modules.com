<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * packagist check
 */
class Migration_Composer_20130917041723 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "CREATE TABLE IF NOT EXISTS `module_commits` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `module_id` int(10) unsigned NOT NULL,
		  `sha` varchar(255) NOT NULL,
		  `version` int(10) unsigned NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, "DROP TABLE  `module_commits`;");
	}

}
