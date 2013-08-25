<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller
{
    public function __construct($request, $response)
    {
        $this->query = ORM::factory('Module');

        parent::__construct($request, $response);
    }

    public function action_show()
    {
        $username = $this->request->param('username');
        $name     = $this->request->param('name');

        $module = $this->query
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find();

        if ( ! $module->loaded())
        {
            throw new HTTP_Exception_404('Module :username/:name not found',
                array(':username' => $username, ':name' => $name));
        }

        $view = new View_Module_Show($module);
        $this->renderBody($view);
    }

    public function action_by_username()
    {
        $username = $this->request->param('username');

        $this->query->where('username', '=', $username);

        $count = $this->query->reset(FALSE)->count_all();

        if ($count == 0)
        {
            throw new HTTP_Exception_404('No modules found for :username',
                array(':username' => $username));
        }

        $compatibility = $this->getRequestedCompatibility();

        $this->query->where_compatible_with($compatibility);

        $view = new View_Module_ByUsername($this->query, $username);
        $this->renderBody($view, 'module/by-username');
    }

    public function action_index()
    {
        $default_compatibility = Model_Kohana_Version::latest();
        $compatibility         = $this->getRequestedCompatibility($default_compatibility);

        $this->query->where_compatible_with($compatibility);

        $view = new View_Module_Index($this->query);
        $this->renderBody($view, 'partials/module/index');
    }

    public function action_search()
    {
        $term = $_GET['query'];

        $compatibility = $this->getRequestedCompatibility();

        $this->query
            ->filterBySearchTerm($term)
            ->where_compatible_with($compatibility);

        $view = new View_Module_Search($this->query, $term);
        $this->renderBody($view, 'partials/module/index');
    }

    private function getRequestedCompatibility($default = 'any')
    {
        return Arr::get($_GET, 'compatibility', $default);
    }

    private function renderBody($view, $template = NULL)
    {
        $renderer = Kostache_Layout::factory();
        $body     = $renderer->render($view, $template);

        $this->response->body($body);
    }
}
