<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
    'default' => array
    (
        'type'       => 'MySQL',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname'   => isset($_SERVER['DB1_HOST']) ? $_SERVER['DB1_HOST'] : '127.0.0.1',
            'database'   => isset($_SERVER['DB1_NAME']) ? $_SERVER['DB1_NAME'] : 'kohana-modules',
            'username'   => isset($_SERVER['DB1_USER']) ? $_SERVER['DB1_USER'] : 'kohana-modules',
            'password'   => isset($_SERVER['DB1_PASS']) ? $_SERVER['DB1_PASS'] : 'kohana-modules',
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
        'profiling'    => Kohana::$environment !== Kohana::PRODUCTION,
    ),
);
