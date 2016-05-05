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
		'compactSingleActionToLink' => true
	];

	protected $actions;
	protected $currentActions;

	function __construct($actions, $attributes = [])
	{
		$this->actions = collect($actions);
		$this->attributes = array_merge($this->attributes, $attributes);
	}

	public function check($instance = null)
	{
		$this->currentActions = $this->actions->filter(function ($action) use ($instance) {
			return $action->check($instance);
		});
		return $this->currentActions->count() > 0;
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
		$attributes = $this->attributes + $this->defaults;
		$attributes['actions'] = $this->currentActions;
		return $attributes;
	}
}
