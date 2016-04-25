<?php

namespace Bozboz\Admin\Reports\Actions;

class DestroyAction extends FormAction
{
	protected $attributes = [
		'method' => 'DELETE',
		'label' => 'Delete',
		'icon' => 'fa fa-trash',
		'class' => 'btn-danger btn-destroy',
		'warn' => 'Are you sure you want to delete?'
	];
}
