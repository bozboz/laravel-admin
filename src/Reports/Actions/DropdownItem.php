<?php

namespace Bozboz\Admin\Reports\Actions;

class DropdownItem extends LinkAction
{
	public function getView()
	{
		return 'admin::report-actions.dropdown-item';
	}
}