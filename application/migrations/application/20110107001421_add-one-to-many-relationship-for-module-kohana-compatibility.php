<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Add one-to-many relationship for module/Kohana compatibility
 */
class Migration_Application_20110107001421 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function up(Kohana_Database $db)
	{
        $db->query(NULL,
            'CREATE TABLE `kohana_versions` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
            ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci'
        );

        $db->query(NULL,
            'CREATE TABLE `module_compatibilities` (
                `module_id` INT UNSIGNED NOT NULL ,
                `kohana_version_id` INT UNSIGNED NOT NULL
            ) ENGINE = InnoDB'
        );

        $db->query(NULL, 'ALTER TABLE `module_compatibilities` ADD INDEX ( `module_id` )');
        $db->query(NULL, 'ALTER TABLE `module_compatibilities` ADD INDEX ( `kohana_version_id` )');

        $db->query(NULL, 'ALTER TABLE `module_compatibilities`
            ADD CONSTRAINT `module_compatibilities_ibfk_1` FOREIGN KEY ( `module_id` )
            REFERENCES `modules` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE'
        );

        $db->query(NULL, 'ALTER TABLE `module_compatibilities`
            ADD CONSTRAINT `module_compatibilities_ibfk_2` FOREIGN KEY ( `kohana_version_id` )
            REFERENCES `kohana_versions` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE'
        );
	}

	/**
	 * Run queries need to remove this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function down(Kohana_Database $db)
	{
        $db->query(NULL, 'DROP TABLE `module_compatibilities`');
        $db->query(NULL, 'DROP TABLE `kohana_versions`');
	}
}
