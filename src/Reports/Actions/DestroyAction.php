<?php

namespace Bozboz\Admin\Reports\Actions;

class DestroyAction extends FormAction
{
	protected $attributes = [
		'method' => 'DELETE',
		'label' => 'Delete',
		'icon' => 'fa fa-minus-square',
		'class' => 'btn-danger btn-destroy',
		'warn' => 'Are you sure you want to delete?'
	];
}
