<?php namespace Bozboz\Admin\Http\Controllers;

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

	public function createPermissions($stack)
	{
		$stack->add('create_user');
	}

	public function editPermissions($stack, $id)
	{
		$stack->add('edit_user', (int)$id);
		$stack->add('edit_profile', (int)$id);
	}

	public function deletePermissions($stack, $id)
	{
		$stack->add('delete_user', (int)$id);
	}
}
