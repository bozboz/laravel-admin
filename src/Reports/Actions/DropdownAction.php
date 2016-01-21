<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\ChecksPermissions;

class DropdownAction extends Action
{
	protected $actions;

	function __construct($actions, $attributes = [])
	{
		$this->actions = $actions;
		$this->attributes = array_merge($this->attributes, $attributes);
	}

	public function check(ChecksPermissions $context)
	{
		$this->actions = $this->actions->filter(function ($action) use ($context) {
			return $action->check($context);
		});
		return $this->actions->count() > 0;
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown';
	}

	public function getViewData($row)
	{
		return ['actions' => $this->actions];
	}
}
