<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404
{
    public function get_response()
    {
        if (!in_array(Kohana::$environment, array(Kohana::PRODUCTION, Kohana::STAGING)))
        {
            return parent::get_response();
        }

        $view = new View_Error_404($this);
        $body = Kostache_Layout::factory()->render($view);

        $response = Response::factory();
        $response->status(404);
        $response->body($body);

        return $response;
    }
}
