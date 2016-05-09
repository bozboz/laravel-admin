<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Dropdown extends Presenter
{
	private $actions;

	public function __construct($actions, $label, $icon = null, $attributes = [])
	{
		$this->actions = $actions;
		$this->label = $label;
		$this->icon = $icon;

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
			'btnClass' => 'btn-default',
			'attributes' => $this->compileAttributes(),
		];
	}

	protected function compileAttributes()
	{
		$attributes = $this->attributes;

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
