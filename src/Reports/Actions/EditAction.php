<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Link;

class EditAction extends Action
{
	public function __construct($action, $permission)
	{
		parent::__construct(
			new Link($action, 'Edit', 'fa fa-pencil', ['class' => 'btn-info btn-edit']),
			new IsValid($permission)
		);
	}
}
