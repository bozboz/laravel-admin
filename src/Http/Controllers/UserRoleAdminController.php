<?php

namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Users\RoleAdminDecorator;

class UserRoleAdminController extends ModelAdminController
{
	public function __construct(RoleAdminDecorator $decorator)
	{
		parent::__construct($decorator);
	}
}