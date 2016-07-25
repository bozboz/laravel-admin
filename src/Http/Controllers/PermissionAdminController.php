<?php

namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Permissions\PermissionAdminDecorator;
use Bozboz\Admin\Permissions\RestrictAllPermissionsTrait;

class PermissionAdminController extends ModelAdminController
{
	protected $useActions = true;

	use RestrictAllPermissionsTrait;

	public function __construct(PermissionAdminDecorator $decorator)
	{
		parent::__construct($decorator);
	}

	protected function getRestrictRule()
	{
		return 'manage_permissions';
	}
}
