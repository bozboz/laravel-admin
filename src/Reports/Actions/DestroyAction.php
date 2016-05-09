<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Form;

class DestroyAction extends Action
{
	public function __construct($action, $permission)
	{
		parent::__construct(
			new Form($action, 'Delete', 'fa fa-trash', [
				'class' => 'btn-danger btn-sm btn-destroy',
				'data-warn' => 'Are you sure you want to delete?'
			], [
				'method' => 'DELETE'
			]),
			new IsValid($permission)
		);
	}
}
