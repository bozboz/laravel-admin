<?php

namespace Bozboz\Admin\Reports\Actions\Permissions;

class IsValid
{
	private $permission;

	public function __construct(callable $permission)
	{
		$this->permission = $permission;
	}

	public function check($instance)
	{
		return call_user_func($this->permission, $instance);
	}
}
