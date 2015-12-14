<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Permissions\Rules\Rule;
use Bozboz\Permissions\UserInterface;

class UserRule extends Rule
{
	public function validFor(UserInterface $user, $instance)
	{
		return $this->isOwnProfile($user, $instance) && $this->checkUserPermissions($user, $instance);
	}

	protected function isOwnProfile(UserInterface $user, $instance)
	{
		return $user->id === $instance->id;
	}
}
