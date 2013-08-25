<?php defined('SYSPATH') or die('No direct script access.');

abstract class View_Error extends View_Layout
{
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function title()
    {
        return Response::$messages[$this->code];
    }

    public function message()
    {
        return $this->exception->getMessage();
    }
}
