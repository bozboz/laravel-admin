<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Permissions\Rules\GlobalRule;
use Bozboz\Permissions\UserInterface;

class UserRule extends GlobalRule
{
	public function validFor(UserInterface $user, $param)
	{
		return $this->isOwnProfile($user, $param) && $this->checkUserPermissions($user, $param);
	}

	protected function isOwnProfile(UserInterface $user, $id)
	{
		return $user->id === $id;
	}
}
