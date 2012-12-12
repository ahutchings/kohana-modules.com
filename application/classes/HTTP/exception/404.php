<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404
{
    public function get_response()
    {
        $response = Response::factory();

        // Construct the response body
        $body = View::factory('template')
            ->bind('content', $content)
            ->bind('title', $title)
            ->bind('tagline', $tagline);

        $content = View::factory('errors/http');
        $content->code    = 404;
        $content->message = $this->getMessage();

        $title = Response::$messages[404].' - ';

        $tagline = __('Indexing <span>:modules</span> modules from <span>:developers</span> developers.',
            array(
                ':modules' => ORM::factory('module')->count_all(),
                ':developers' => DB::query(Database::SELECT, 'SELECT DISTINCT username FROM modules')->execute()->count()
                )
            );

        $response->status(404);

        $response->body($body->render());

		return $response;
    }
}
