<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Decorators\UserAdminDecorator;
use Bozboz\Permissions\Facades\Gate;

class UserAdminController extends ModelAdminController
{
	public function __construct(UserAdminDecorator $user)
	{
		parent::__construct($user);
	}

	public function viewPermissions($stack)
	{
		$stack->add('view_users');
	}

	public function createPermissions($stack, $instance)
	{
		$stack->add('create_user', $instance);
	}

	public function editPermissions($stack, $instance)
	{
		$stack->add('edit_user', $instance);
		$stack->add('edit_profile', $instance);
	}

	public function deletePermissions($stack, $instance)
	{
		$stack->add('delete_user', $instance);
	}
}
