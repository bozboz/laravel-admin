<?php

namespace Bozboz\Admin\Reports\Actions;

class DropdownLabelItem extends Action
{
	public function __construct($label)
	{
		parent::__construct(null, [
			'label' => $label,
		]);
	}

	public function getView()
	{
		return 'admin::report-actions.dropdown-label-item';
	}
}
