<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules extends Controller
{
    const DEFAULT_SORT_PARAM = 'watchers';

    private $_sort_columns_by_param = array
    (
        'watchers' => 'watchers',
        'forks'    => 'forks',
        'stars'    => 'stars',
        'added'    => 'created_at',
    );

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        $this->query = ORM::factory('Module');
        $this->_setQueryOrderBy();
    }

    private function _setQueryOrderBy()
    {
        $column = $this->_getOrderByColumn();
        return $this->query->order_by($column, 'DESC');
    }

    private function _getOrderByColumn()
    {
        $sort_param = $this->request->query('sort');

        if ( ! isset($this->_sort_columns_by_param[$sort_param])) {
            $sort_param = self::DEFAULT_SORT_PARAM;
        }

        return $this->_sort_columns_by_param[$sort_param];
    }

    public function action_show()
    {
        $username = $this->request->param('username');
        $name     = $this->request->param('name');

        $module = $this->query
            ->where('username', '=', $username)
            ->where('name', '=', $name)
            ->find();

        if ( ! $module->loaded()) {
            throw new HTTP_Exception_404(
                'Module :username/:name not found',
                array(':username' => $username, ':name' => $name)
            );
        }

        $view = new View_Module_Show($module);
        $this->_renderBody($view);
    }

    public function action_by_username()
    {
        $username = $this->request->param('username');

        $this->query->where('username', '=', $username);

        $count = $this->query->reset(false)->count_all();

        if ($count == 0) {
            throw new HTTP_Exception_404(
                'No modules found for :username',
                array(':username' => $username)
            );
        }

        $compatibility = $this->_getRequestedCompatibility();

        $this->query->where_compatible_with($compatibility);

        $view = new View_Module_ByUsername($this->query, $username);
        $this->_renderBody($view);
    }

    public function action_index()
    {
        $default_compatibility = Model_Kohana_Version::latest();
        $compatibility         = $this->_getRequestedCompatibility($default_compatibility);

        $this->query->where_compatible_with($compatibility);

        $view = new View_Module_Index($this->query);
        $this->_renderBody($view);
    }

    public function action_search()
    {
        $term = $this->request->query('query');

        $compatibility = $this->_getRequestedCompatibility();

        $this->query
            ->filterBySearchTerm($term)
            ->where_compatible_with($compatibility);

        $view = new View_Module_Search($this->query, $term);
        $this->_renderBody($view);
    }

    private function _getRequestedCompatibility($default = 'any')
    {
        $compatibility = $this->request->query('compatibility');

        if ( ! $compatibility) {
            $compatibility = $default;
        }

        return $compatibility;
    }

    private function _renderBody($view)
    {
        $renderer = Kostache_Layout::factory();
        $body     = $renderer->render($view);

        $this->response->body($body);
    }
}
