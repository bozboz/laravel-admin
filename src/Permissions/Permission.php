<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Base\ModelInterface;
use Bozboz\Admin\Base\SanitisesInputTrait;
use Bozboz\Admin\Users\Role;
use Bozboz\Permissions\Permission as BasePermission;

class Permission extends BasePermission implements ModelInterface
{
	use SanitisesInputTrait;

	public function getValidator()
	{
		return new PermissionValidator;
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}
