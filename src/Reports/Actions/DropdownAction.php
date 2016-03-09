<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\ChecksPermissions;

class DropdownAction extends Action
{
	protected $defaults = [
		'warn' => null,
		'btnClass' => 'btn-default',
		'dropdownClass' => '',
		'icon' => '',
		'label' => 'Unknown',
	];

	protected $actions;

	function __construct($actions, $attributes = [])
	{
		$this->actions = collect($actions);
		$this->attributes = array_merge($this->attributes, $attributes);
	}

	public function check(ChecksPermissions $context)
	{
		$this->actions = $this->actions->filter(function ($action) use ($context) {
			return $action->check($context);
		});
		return $this->actions->count() > 0;
	}

	public function setInstance($instance)
	{
		$this->instance = $instance;

		$this->actions->each(function($action) use ($instance) {
			$action->setInstance($instance);
		});
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown';
	}

	public function getViewData()
	{
		$attributes = $this->getAttributes() + $this->defaults;
		$attributes['actions'] = $this->actions;
		return $attributes;
	}
}
