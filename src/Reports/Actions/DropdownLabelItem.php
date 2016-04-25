<?php

namespace Bozboz\Admin\Reports\Actions;

class DropdownLabelItem extends Action
{
	public function __construct($label)
	{
		$this->label = $label;
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown-label-item';
	}

	public function getViewData()
	{
		return $this->getAttributes();
	}
}