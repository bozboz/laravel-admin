<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Decorators\UserAdminDecorator;
use Bozboz\Permissions\Facades\Gate;

class UserAdminController extends ModelAdminController
{
	public function __construct(UserAdminDecorator $user)
	{
		parent::__construct($user);
	}

	public function canEdit($id)
	{
		return Gate::allows('edit_profile', (int)$id) || parent::canEdit($id);
	}
}
