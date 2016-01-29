<?php

namespace Bozboz\Admin\Reports\Actions;

class DropdownUnlinkedItem extends Action
{
	public function __construct($label)
	{
		$this->label = $label;
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown-unlinked-item';
	}

	public function getViewData()
	{
		return $this->getAttributes();
	}
}