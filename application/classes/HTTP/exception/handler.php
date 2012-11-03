<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Exception_Handler
{
    public static function handle(Exception $e)
    {
        if (get_class($e) === 'HTTP_Exception_404')
        {
            // We can display the code and message in this case
            $code    = $e->getCode();
            $message = $e->getMessage();
        }
        else
        {
            // Default HTTP code to display
            $code = 500;

            // Default error message to display
            $message = 'Oops, there was a problem.';
        }
        
        // Construct the response body
        $body = View::factory('template')
            ->bind('content', $content)
            ->bind('title', $title)
            ->bind('tagline', $tagline);

        $content = View::factory('errors/http')
            ->set('code', $code)
            ->set('message', $message);

        $title = Response::$messages[$code].' - ';

        $tagline = __('Indexing <span>:modules</span> modules from <span>:developers</span> developers.',
            array(
                ':modules' => ORM::factory('module')->count_all(),
                ':developers' => DB::query(Database::SELECT, 'SELECT DISTINCT username FROM modules')->execute()->count()
                )
            );

        $response = new Response;
        $response->status($code);

        echo $response->body($body)->send_headers()->body();

		// Create a text version of the exception
		$error = Kohana_Exception::text($e);

		if (is_object(Kohana::$log))
		{
			// Add this exception to the log
			Kohana::$log->add(Log::ERROR, $error);

			// Make sure the logs are written
			Kohana::$log->write();
		}
		
		return TRUE;
    }
}
