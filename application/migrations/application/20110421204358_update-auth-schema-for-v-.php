<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Update auth schema for v3.1.2
 *
 * @see https://github.com/kohana/orm/commit/1eee5868040630af20fe2c6f667bc42d00b71f1a
 * @see https://github.com/kohana/orm/commit/9720af86d005d3590bb85f204e753cee520c4507
 */
class Migration_Application_20110421204358 extends Minion_Migration_Base {

    /**
     * Run queries needed to apply this migration
     *
     * @param Kohana_Database Database connection
     */
    public function up(Kohana_Database $db)
    {
        $db->query(NULL, 'ALTER TABLE `users`
            CHANGE `password` `password` VARCHAR( 64 )');

        $db->query(NULL, 'ALTER TABLE `user_tokens`
            CHANGE `token` `token` VARCHAR( 40 )');

        $db->query(NULL, 'ALTER TABLE `user_tokens`
            ADD `type` VARCHAR( 100 ) NOT NULL');
    }

    /**
     * Run queries needed to remove this migration
     *
     * @param Kohana_Database Database connection
     */
    public function down(Kohana_Database $db)
    {
        $db->query(NULL, 'ALTER TABLE `users`
            CHANGE `password` `password` CHAR( 50 )');

        $db->query(NULL, 'ALTER TABLE `user_tokens`
            CHANGE `token` `token` VARCHAR( 32 )');

        $db->query(NULL, 'ALTER TABLE `user_tokens`
            DROP `type`');
    }
}
