<?php namespace Bozboz\Admin\Meta;

use Bozboz\Admin\Models\Page;
use Illuminate\Config\Repository as Config;
use App;

class Provider
{
	public function __construct(Page $page, Config $config)
	{
		$this->page = $page;
		$this->config = $config;
	}

	public static function forPage(Page $page)
	{
		return new static($page, App::make('config'));
	}

	public function getTitle()
	{
		if (empty($this->page->meta_title)) {
			return $this->config->get('app.site_title', 'Default Site Title');
		} else {
			return $this->page->meta_title;
		}
	}

	public function getDescription()
	{
		if (empty($this->page->meta_description)) {
			return $this->config->get('app.meta_description', 'Default Meta Description');
		} else {
			return $this->page->meta_description;
		}
	}
}
