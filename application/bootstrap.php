<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
    // Application extends the core
    require APPPATH.'classes/Kohana'.EXT;
}
else
{
    // Load empty core extension
    require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/New_York');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Auto-load composer modules.
 */
require APPPATH.'vendor/autoload.php';

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

Cookie::$salt = '12345';

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
    'index_file' => FALSE,
    'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
    'caching'    => Kohana::$environment === Kohana::PRODUCTION,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_StdOut(), array(
  Log::NOTICE,
  Log::INFO,
  Log::DEBUG
  ));

Kohana::$log->attach(new Log_StdErr(), array(
  Log::EMERGENCY,
  Log::ALERT,
  Log::CRITICAL,
  Log::ERROR,
  Log::WARNING
  ));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'auth'        => MODPATH.'auth',       // Basic authentication
    'cache'       => MODPATH.'cache',      // Caching with multiple backends
    'database'    => MODPATH.'database',   // Database access
    'kostache'    => APPPATH.'vendor/zombor/kostache',
    'minion'      => MODPATH.'minion',
    'migrations'  => MODPATH.'vendor/kohana-minion/tasks-migrations',
    'notices'     => MODPATH.'notices',
    'orm'         => MODPATH.'orm',        // Object Relationship Mapping
    'pagination'  => MODPATH.'pagination', // Paging of results
    'sitemap'     => MODPATH.'sitemap',
    'tasks-cache' => MODPATH.'tasks-cache',
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('error', 'error')
    ->defaults(array(
        'controller' => 'pages',
        'action'     => 'error'
    ));

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory' => 'admin',
        'controller' => 'dashboard',
        'action' => 'index',
    ));

Route::set('pages', 'pages/<page>',
    array(
        'page' => '(about|feedback|add-a-module)',
    ))
    ->defaults(array(
        'controller' => 'pages',
        'action'     => 'display',
    ));

Route::set('modules', 'modules/index')
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'index',
    ));

Route::set('modules_by_username', 'modules/<username>')
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'by_username',
    ));

Route::set('modules_show', 'modules/<username>/<name>',
    array(
        'name' => '[a-zA-Z0-9-\._]++',
    ))
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'show',
    ));

Route::set('modules_search', 'search')
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'search'
    ));

Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'index',
    ));
