<?php defined('SYSPATH') or die('No direct script access.');

class View_Module_Sort
{
	private $sortKeys = array('watchers', 'forks', 'added');
	private $defaultSortKey = 'watchers';

	public function __construct($defaultVersion = 'any')
	{
		$this->defaultVersion = $defaultVersion;
	}

	public function versions()
	{
		return array_map(array($this, 'formatVersion'), $this->getVersions());
	}

	private function getVersions()
	{
		$versions = ORM::factory('Kohana_Version')
            ->order_by('name', 'DESC')
            ->find_all()
            ->as_array();

        $anyVersion = ORM::factory('Kohana_Version');
        $anyVersion->name = 'any';

        $versions[] = $anyVersion;

        return $versions;
	}

	private function formatVersion($version)
	{
		return array(
			'name'     => ucfirst($version->name),
			'selected' => $this->isVersionSelected($version),
			'url'      => $this->getVersionURL($version)
			);
	}

	private function isVersionSelected($version)
	{
		return $this->getSelectedVersion() === $version->name;
	}

	private function getSelectedVersion()
	{
		return Arr::get($_GET, 'compatibility', $this->defaultVersion);
	}

	private function getVersionURL($version)
	{
		return Arr::get($_SERVER, 'PATH_INFO').URL::query(array('compatibility' => strtolower($version->name)));
	}

	public function sorts()
	{
		return array_map(array($this, 'formatSortKey'), $this->sortKeys);

	}

	private function formatSortKey($key)
	{
		return array(
			'name'     => ucfirst($key),
			'selected' => $this->isSortKeySelected($key),
			'url'      => $this->getSortURL($key)
			);
	}

	private function isSortKeySelected($key)
	{
		return Arr::get($_GET, 'sort', $this->defaultSortKey) === $key;
	}

	private function getSortURL($key)
	{
		return Arr::get($_SERVER, 'PATH_INFO').URL::query(array('sort' => $key));
	}
}
