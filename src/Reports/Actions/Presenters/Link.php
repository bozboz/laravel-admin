<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Link extends Presenter
{
	private $url;
	private $icon;

	public function __construct($url, $label, $icon = null, $attributes = [])
	{
		$this->url = ($url instanceof Urls\Contract) ? $url : new Urls\Action($url);
		$this->label = $label;
		$this->icon = $icon;

		parent::__construct($attributes);
	}

	public function getView()
	{
		return 'admin::report-actions.link';
	}

	public function getViewData()
	{
		return [
			'icon' => $this->icon,
			'label' => $this->label,
			'attributes' => $this->compileAttributes(),
		];
	}

	protected function compileAttributes()
	{
		$attributes = $this->attributes;

		$attributes['href'] = $this->url->compile($this->instance);
		$attributes['class'] = trim($attributes['class'] . ' btn btn-sm');

		return $attributes;
	}
}
