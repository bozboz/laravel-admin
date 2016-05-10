<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Dropdown extends Presenter
{
	private $actions;
	private $dropdownAttributes = ['class' => ''];

	public function __construct($actions, $label, $icon = null, $attributes = [], $dropdownAttributes = [])
	{
		$this->actions = $actions;
		$this->label = $label;
		$this->icon = $icon;

		foreach($dropdownAttributes as $key => $value) {
			$this->dropdownAttributes[$key] = $value;
		}

		parent::__construct($attributes);
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown';
	}

	public function getViewData()
	{
		return $this->attributes + [
			'icon' => $this->icon,
			'label' => $this->label,
			'actions' => $this->actions,
			'attributes' => $this->compileAttributes(),
			'dropdownAttributes' => $this->compileDropdownAttributes(),
		];
	}
	protected function compileAttributes()
	{
		$attributes = $this->attributes;

		$attributes['class'] = trim($attributes['class'] . ' dropdown-toggle btn');
		$attributes['href'] = "#";
		$attributes['data-toggle'] = "dropdown";
		$attributes['aria-expanded'] = "false";

		return $attributes;
	}

	protected function compileDropdownAttributes()
	{
		$attributes = $this->dropdownAttributes;

		$attributes['class'] = trim($attributes['class'] . ' btn-group');

		return $attributes;
	}

	public function setInstance($instance)
	{
		$this->actions->each(function($action) use ($instance) {
			$action->setInstance($instance);
		});

		parent::setInstance($instance);
	}
}
