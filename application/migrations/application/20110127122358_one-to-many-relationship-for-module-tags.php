<?php defined('SYSPATH') or die('No direct script access.');

/**
 * One-to-many relationship for module tags
 */
class Migration_Application_20110127122358 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function up(Kohana_Database $db)
	{
        $db->query(NULL, 'ALTER TABLE `modules_tags` DROP FOREIGN KEY `modules_tags_ibfk_1`');

        $db->query(NULL, 'ALTER TABLE `modules_tags` DROP `tag_id`');

        $db->query(NULL, 'ALTER TABLE `modules_tags` ADD `name` VARCHAR( 255 )
            CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');

        $db->query(NULL, 'DROP TABLE `tags`');
        
        $db->query(NULL, 'RENAME TABLE `modules_tags` TO `tags`');
        
        $db->query(NULL, 'ALTER TABLE `tags` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        
        $db->query(NULL, 'ALTER TABLE `modules` DROP `tags`');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function down(Kohana_Database $db)
	{
	    $db->query(NULL, 'ALTER TABLE `modules` ADD `tags` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
	    
	    $db->query(NULL, 'ALTER TABLE `tags` DROP `id`');
	    
        $db->query(NULL, 'RENAME TABLE `tags` TO `modules_tags`');
	    
	    $db->query(NULL, 'CREATE TABLE IF NOT EXISTS `tags` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `slug` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $db->query(NULL, 'ALTER TABLE `modules_tags` DROP `name`');

        $db->query(NULL, 'ALTER TABLE `modules_tags` ADD `tag_id` INT( 11 ) UNSIGNED NOT NULL ,
            ADD INDEX ( `tag_id` )');
            
        $db->query(NULL, 'TRUNCATE TABLE `modules_tags`');

        $db->query(NULL, 'ALTER TABLE `modules_tags`
            ADD CONSTRAINT `modules_tags_ibfk_1` FOREIGN KEY ( `tag_id` )
            REFERENCES `kohana-modules_development`.`tags` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE');
	}
}
