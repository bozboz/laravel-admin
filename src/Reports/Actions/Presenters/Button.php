<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Button extends Presenter
{
	private $icon;

	public function __construct($label, $icon = null, $attributes = [])
	{
		$this->label = $label;
		$this->icon = $icon;

		parent::__construct($attributes);
	}

	public function getView()
	{
		return 'admin::report-actions.button';
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

		$attributes['class'] = trim($attributes['class'] . ' btn');

		return $attributes;
	}
}
