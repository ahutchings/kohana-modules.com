<?php defined('SYSPATH') or die('No direct access allowed.');

$config = array
(
    'default' => array
    (
        'type'       => 'mysql',
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
            'hostname'   => 'localhost',
            'database'   => 'kohana-modules',
            'username'   => 'kohana-modules',
            'password'   => 'kohana-modules',
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
        'profiling'    => TRUE,
    ),
);

if (Kohana::$environment === Kohana::PRODUCTION)
{
    return $config;
}
elseif (Kohana::$environment === Kohana::STAGING)
{
    $config['default']['connection']['database'] = 'kohana-modules_staging';
    $config['default']['connection']['username'] = 'km_staging';
    $config['default']['connection']['password'] = 'km_staging';
    
    return $config;
}
elseif (Kohana::$environment === Kohana::DEVELOPMENT)
{
    $config['default']['connection']['database'] = 'kohana-modules_development';
    $config['default']['connection']['username'] = 'km_development';
    $config['default']['connection']['password'] = 'km_development';
    
    return $config;
}
else
{
    throw new Kohana_Exception('Database config for environment :environment not found',
        array(':environment' => Kohana::$environment));
}
