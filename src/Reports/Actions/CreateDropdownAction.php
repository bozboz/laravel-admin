<?php

namespace Bozboz\Admin\Reports\Actions;

class CreateDropdownAction extends DropdownAction
{
	protected $attributes = [
		'label' => 'New',
		'icon' => 'fa fa-plus-square',
		'btnClass' => 'btn-success',
		'dropdownClass' => 'space-left pull-right',
	];
}
