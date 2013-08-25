<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller
{
    public function __construct($request, $response)
    {
        $this->renderer = Kostache_Layout::factory();

        parent::__construct($request, $response);
    }

    public function action_display()
    {
        $page = $this->request->param('page');
        $viewClass = $this->getViewClassForPage($page);

        $view = new $viewClass();
        $responseBody = $this->renderer->render($view, 'page/'.$page);

        $this->response->body($responseBody);
    }

    private function getViewClassForPage($page)
    {
        $words = preg_split('/\-/', $page);
        $pageClass = array_reduce($words, array($this, 'reduceWordIntoClassName'));
        return 'View_Page_'.$pageClass;
    }

    private function reduceWordIntoClassName($className, $word)
    {
        return $className.ucfirst($word);
    }
}
