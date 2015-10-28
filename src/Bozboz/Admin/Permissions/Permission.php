<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Models\BaseInterface;
use Bozboz\Admin\Models\User;
use Bozboz\Admin\Traits\SanitisesInputTrait;
use Bozboz\Permissions\Permission as BasePermission;

class Permission extends BasePermission implements BaseInterface
{
	use SanitisesInputTrait;

	public function getValidator()
	{
		return new PermissionValidator;
	}

	public function user()
	{
		return $this->belongsTo('Bozboz\Admin\Models\User');
	}
}
