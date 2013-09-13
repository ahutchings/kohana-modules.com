<?php defined('SYSPATH') or die('No direct script access.');

class View_Module_Show extends View_Layout
{
    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function title()
    {
        return $this->module->username.'/'.$this->module->name;
    }

    public function meta_description()
    {
        if (Valid::not_empty($this->module->description))
        {
            return $this->module->description;
        }
    }

    public function name()
    {
        return $this->module->name;
    }

    public function username()
    {
        return $this->module->username;
    }

    public function description()
    {
        return $this->module->description;
    }

    public function watchers()
    {
        return $this->module->watchers;
    }

	public function forks()
	{
		return $this->module->forks;
	}

	public function stars()
	{
		return $this->module->stars;
	}

    public function url()
    {
        return $this->module->url();
    }

    public function homepage_url()
    {
        if ($this->module->homepage AND Valid::external_url($this->module->url('homepage')))
        {
            return $this->module->url('homepage');
        }
    }

    public function wiki_url()
    {
        if ($this->module->has_wiki)
        {
            return $this->module->url('wiki');
        }
    }

    public function issues_url()
    {
        if ($this->module->has_issues)
        {
            return $this->module->url('issues');
        }
    }

    public function open_issues()
    {
        return $this->module->open_issues;
    }

    public function not_production()
    {
        return Kohana::$environment !== Kohana::PRODUCTION;
    }

    public function kohana_versions()
    {
        $versions = array();

        foreach (Model_Kohana_Version::names() as $name) {
            $versions[] = array(
                'name' => $name,
                'compatible' => $this->module->isCompatibleWithKohanaVersion($name)
                );
        }

        return $versions;
    }

    public function has_tags()
    {
        return count($this->module->tags->find_all()) > 0;
    }

    public function tags()
    {
        return $this->module->tags
            ->order_by('name', 'DESC')
            ->limit(5)
            ->find_all();
    }

	public function supports_composer()
	{
		return $this->module->has_composer;
	}
}
