<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Exception extends Kohana_Kohana_Exception
{
    public static function response(Exception $e)
    {
        if (!in_array(Kohana::$environment, array(Kohana::PRODUCTION, Kohana::STAGING)))
        {
            return parent::response($e);
        }

        $view = new View_Error_500($e);
        $body = Kostache_Layout::factory()->render($view, 'error');

        $response = Response::factory();
        $response->status(500);
        $response->body($body);

        return $response;
    }
}
