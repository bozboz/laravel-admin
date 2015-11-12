<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Services\Validators\Validator;

class PermissionValidator extends Validator
{
	protected $rules = [
		'param' => 'integer',
		'action' => 'required',
		'user_id' => 'required|exists:users,id'
	];
}
