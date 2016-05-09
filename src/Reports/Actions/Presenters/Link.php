<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Link extends Presenter
{
	private $action;
	private $icon;

	public function __construct($action, $label, $icon = null, $attributes = [])
	{
		$this->action = $action;
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

		$attributes['href'] = $this->getUrl();
		$attributes['class'] = trim($attributes['class'] . ' btn btn-sm');

		return $attributes;
	}

	protected function getUrl()
	{
		if (is_array($this->action)) {
			list($action, $params) = $this->action;
			$params = is_array($params) ? $params : [$params];
		} else {
			$action = $this->action;
			$params = [];
		}

		if ($this->instance) {
			array_push($params, $this->instance->id);
		}

		return action($action, array_filter($params));
	}
}
