<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Link;

class CreateAction extends Action
{
	public function __construct($action, $permission)
	{
		parent::__construct(
			new Link($action, 'New', 'fa fa-plus', [
				'class' => 'btn-success btn-create pull-right',
			]),
			new IsValid($permission)
		);
	}
}
