<?php

namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Permissions\PermissionAdminDecorator;

class PermissionAdminController extends ModelAdminController
{
	public function __construct(PermissionAdminDecorator $decorator)
	{
		parent::__construct($decorator);
	}
}
