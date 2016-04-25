<?php

namespace Bozboz\Admin\Reports\Actions;

class CreateAction extends LinkAction
{
	protected $attributes = [
		'label' => 'New',
		'icon' => 'fa fa-plus',
		'class' => 'btn-success btn-create pull-right',
	];
}
