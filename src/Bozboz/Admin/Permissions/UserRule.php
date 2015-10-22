<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Permissions\Rules\Rule;
use Bozboz\Permissions\UserInterface;

class UserRule extends Rule
{
	public function validFor(UserInterface $user, $param)
	{
//		return parent::validFor($user, $param);
		return $user->id === $param && $user->canPerform($this->alias);
	}
}
