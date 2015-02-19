<?php namespace Bozboz\Admin\Meta;

use Illuminate\Config\Repository as Config;
use App;

class Provider
{
	protected $instance;

	protected $config;

	public function __construct(MetaInterface $instance, Config $config)
	{
		$this->instance = $instance;
		$this->config = $config;
	}

	public static function forPage(MetaInterface $instance)
	{
		return new static($instance, App::make('config'));
	}

	public function getTitle()
	{
		$title = $this->instance->getTitle();

		if ($title) return $title;

		return $this->config->get('app.site_title', 'Default Site Title');
	}

	public function getDescription()
	{
		$description = $this->instance->getDescription();

		if ($description) return $description;

		return $this->config->get('app.meta_description', 'Default Meta Description');
	}
}
