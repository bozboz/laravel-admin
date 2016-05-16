<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Services\Validators\UserValidator;

trait Validates
{
	public function getValidator()
	{
		return new UserValidator;
	}
}
