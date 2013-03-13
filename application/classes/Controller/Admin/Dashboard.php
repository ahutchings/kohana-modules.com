<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin
{
	public function action_index()
	{
		$this->template->title = 'Dashboard - ';
		$this->template->content = View::factory('admin/dashboard')
			->set('open_tickets', $this->_open_issues())
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

	private function _open_issues()
	{
		$client = AuthenticatedGithubClient::instance();
        $repo = $client->api('repo')->show('ahutchings', 'kohana-modules');
		return $repo['open_issues'];
	}
}
