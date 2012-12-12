<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Exception extends Kohana_Kohana_Exception
{
    public static function response(Exception $e)
    {
        if (!in_array(Kohana::$environment, array(Kohana::PRODUCTION, Kohana::STAGING)))
        {
            return parent::response($e);
        }

        $response = Response::factory();

        // Construct the response body
        $body = View::factory('template')
            ->bind('content', $content)
            ->bind('title', $title)
            ->bind('tagline', $tagline);

        $content = View::factory('errors/http');
        $content->code    = 500;
        $content->message = 'Oops, there was a problem.';

        $title = Response::$messages[500].' - ';

        $tagline = __('Indexing <span>:modules</span> modules from <span>:developers</span> developers.',
            array(
                ':modules' => ORM::factory('module')->count_all(),
                ':developers' => DB::query(Database::SELECT, 'SELECT DISTINCT username FROM modules')->execute()->count()
                )
            );

        $response->status(500);

        $response->body($body->render());

        return $response;
    }
}
