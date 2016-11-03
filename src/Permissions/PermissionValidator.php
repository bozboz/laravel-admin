<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Services\Validators\Validator;

class PermissionValidator extends Validator
{
	protected $rules = [
		'param' => 'integer',
		'action' => 'required',
		'role_id' => 'required|exists:roles,id'
	];
}
