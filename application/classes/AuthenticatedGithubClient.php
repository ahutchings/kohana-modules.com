<?php defined('SYSPATH') or die('No direct script access.');

class AuthenticatedGithubClient
{
    protected static $_instance;

    public static function instance()
    {
        if ( ! isset(self::$_instance))
        {
            $client = new Github\Client(
                new Github\HttpClient\CachedHttpClient(array(
                  'cache_dir' => APPPATH.'cache'.DIRECTORY_SEPARATOR.'php-github-api-cache'
                  ))
            );
            $client->authenticate($_SERVER['GITHUB_OAUTH_TOKEN'], null, Github\Client::AUTH_HTTP_TOKEN);

            self::$_instance = $client;
        }

        return self::$_instance;
    }
}
