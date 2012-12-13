<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
    'default' => array
    (
        'type'       => 'PDO',
        'connection' => array(
            /**
             * The following options are available for PDO:
             *
             * string   dsn         Data Source Name
             * string   username    database username
             * string   password    database password
             * boolean  persistent  use persistent connections?
             */
            'dsn'   => 'mysql:host='.$_SERVER['DB1_HOST'].';dbname='.$_SERVER['DB1_NAME'],
            'username'   => $_SERVER['DB1_USER'],
            'password'   => $_SERVER['DB1_PASS'],
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
);
