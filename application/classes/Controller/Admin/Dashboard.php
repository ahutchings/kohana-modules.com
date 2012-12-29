<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin
{
	public function action_index()
	{
		$this->template->title = 'Dashboard - ';
		$this->template->content = View::factory('admin/dashboard')
			->set('open_tickets', $this->_open_tickets())
			->bind('newest', $newest)
			->bind('recently_updated', $recently_updated);

		$newest = ORM::factory('Module')
			->order_by('created_at', 'DESC')
			->limit(5)
			->find_all();

		$recently_updated = ORM::factory('Module')
			->order_by('updated_at', 'DESC')
			->limit(5)
			->find_all();
	}

	private function _open_tickets()
	{
		$open_issues = Kohana::cache('open_issues');

		if ($open_issues === NULL)
		{
			$github = new Github();
			$repo = $github->get_repo('ahutchings', 'kohana-modules');
			$open_issues = $repo->open_issues;

			Kohana::cache('open_issues', $open_issues);
		}

		return $open_issues;
	}
}
