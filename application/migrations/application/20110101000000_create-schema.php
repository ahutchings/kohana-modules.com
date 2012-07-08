<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Creates initial schema.
 */
class Migration_Application_20110101000000 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL,
			'CREATE TABLE `modules` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`created_at` int(11) NOT NULL,
				`updated_at` int(11) NOT NULL,
				`flagged_for_deletion_at` int(11) DEFAULT NULL,
				`name` varchar(255) NOT NULL,
				`description` varchar(255) DEFAULT NULL,
				`username` varchar(255) NOT NULL,
				`forks` int(11) unsigned DEFAULT NULL,
				`watchers` int(11) unsigned DEFAULT NULL,
				`has_wiki` int(1) unsigned DEFAULT NULL,
				`has_issues` int(1) unsigned DEFAULT NULL,
				`has_downloads` int(1) unsigned DEFAULT NULL,
				`homepage` varchar(255) DEFAULT NULL,
				`open_issues` int(11) unsigned DEFAULT NULL,
				`fork` int(1) unsigned DEFAULT NULL,
				`tags` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `username` (`username`,`name`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			'CREATE TABLE `modules_tags` (
				`module_id` int(11) unsigned NOT NULL,
				`tag_id` int(11) unsigned NOT NULL,
				KEY `module_id` (`module_id`),
				KEY `tag_id` (`tag_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			'CREATE TABLE `queue` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`username` varchar(255) NOT NULL,
				`name` varchar(255) NOT NULL,
				`description` varchar(255) DEFAULT NULL,
				`created_at` int(10) unsigned NOT NULL,
				`updated_at` int(10) unsigned NOT NULL,
				`is_ignored` int(1) unsigned NOT NULL,
				`source` varchar(255) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `username` (`username`,`name`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			'CREATE TABLE `roles` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(32) NOT NULL,
				`description` varchar(255) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_name` (`name`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			"INSERT INTO `roles` (`id`, `name`, `description`) VALUES
				(1, 'login', 'Login privileges, granted after account confirmation'),
				(2, 'admin', 'Administrative user, has access to everything.')"
		);

		$db->query(NULL,
			'CREATE TABLE `roles_users` (
				`user_id` int(10) unsigned NOT NULL,
				`role_id` int(10) unsigned NOT NULL,
				PRIMARY KEY (`user_id`,`role_id`),
				KEY `fk_role_id` (`role_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			'CREATE TABLE `tags` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`slug` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			"CREATE TABLE `users` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`email` varchar(127) NOT NULL,
				`username` varchar(32) NOT NULL DEFAULT '',
				`password` char(50) NOT NULL,
				`logins` int(10) unsigned NOT NULL DEFAULT '0',
				`last_login` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_username` (`username`),
				UNIQUE KEY `uniq_email` (`email`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
		);

		$db->query(NULL,
			'CREATE TABLE `user_tokens` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`user_agent` varchar(40) NOT NULL,
				`token` varchar(32) NOT NULL,
				`created` int(10) unsigned NOT NULL,
				`expires` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_token` (`token`),
				KEY `fk_user_id` (`user_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8'
		);

		$db->query(NULL,
			'ALTER TABLE `modules_tags`
				ADD CONSTRAINT `modules_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
				ADD CONSTRAINT `modules_tags_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
		);

		$db->query(NULL,
			'ALTER TABLE `roles_users`
				ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
				ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE'
		);

		$db->query(NULL,
			'ALTER TABLE `user_tokens`
				ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE'
		);
	}

	/**
	 * Run queries need to remove this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL,
			'DROP TABLE `user_tokens`'
		);

		$db->query(NULL,
			'DROP TABLE `roles_users`'
		);

		$db->query(NULL,
			'DROP TABLE `roles`'
		);

		$db->query(NULL,
			'DROP TABLE `users`'
		);

		$db->query(NULL,
			'DROP TABLE `modules_tags`'
		);

		$db->query(NULL,
			'DROP TABLE `modules`'
		);

		$db->query(NULL,
			'DROP TABLE `tags`'
		);

		$db->query(NULL,
			'DROP TABLE `queue`'
		);
	}
}
