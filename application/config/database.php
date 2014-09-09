<?php defined('SYSPATH') or die('No direct access allowed.');

$url = parse_url(getenv('CLEARDB_DATABASE_URL'));

$database = substr($url['path'], 1);
$hostname = $url['host'];
$username = $url['user'];
$password = $url['pass'];

return array
(
    'default' => array
    (
        'type'       => 'MySQLi',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             * array    variables    system variables as "key => value" pairs
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname'   => $hostname,
            'database'   => $database,
            'username'   => $username,
            'password'   => $password,
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
);
