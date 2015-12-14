<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Permissions\Rules\GlobalRule;
use Bozboz\Permissions\UserInterface;

class UserRule extends GlobalRule
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
