<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Base\ModelInterface;
use Bozboz\Admin\Users\User;
use Bozboz\Admin\Base\SanitisesInputTrait;
use Bozboz\Permissions\Permission as BasePermission;

class Permission extends BasePermission implements ModelInterface
{
	use SanitisesInputTrait;

	public function getValidator()
	{
		return new PermissionValidator;
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
