<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

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
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

//-- Configuration and initialization -----------------------------------------

/**
* Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
*/
if (getenv('KOHANA_ENV') !== FALSE)
{
    Kohana::$environment = getenv('KOHANA_ENV');
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
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
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
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'auth'       => MODPATH.'auth',       // Basic authentication
    'database'   => MODPATH.'database',   // Database access
    'orm'        => MODPATH.'orm',        // Object Relationship Mapping
    'pagination' => MODPATH.'pagination', // Paging of results
    'notices'    => MODPATH.'notices',
    'yform'      => MODPATH.'yform',
    'github'     => MODPATH.'github',
    'sitemap'    => MODPATH.'sitemap',
    'cache'      => MODPATH.'cache',
    'cron-controller' => MODPATH.'cron-controller',
    ));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory' => 'admin',
        'controller' => 'dashboard',
        'action' => 'index',
    ));
 
Route::set('pages', 'pages/<page>',
    array(
        'page' => '(about|feedback|suggest)',
    ))
    ->defaults(array(
        'controller' => 'pages',
        'action'     => 'display',
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

Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'modules',
        'action'     => 'index',
    ));

if ( ! defined('SUPPRESS_REQUEST'))
{
    /**
     * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
     * If no source is specified, the URI will be automatically detected.
     */
    $request = Request::instance();
    
    try
    {
        $request->execute();
    }
    catch (Exception $e)
    {
        if (Kohana::$environment !== Kohana::PRODUCTION)
        {
            throw $e;
        }
        
        // Log the error
        Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

        // Create a 404 response
        $request->status = 404;
        $request->response = View::factory('template')
            ->set('title', '404 | ')
            ->set('content', View::factory('errors/404'));
    }
        
    echo $request->send_headers()->response;
}
