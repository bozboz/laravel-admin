<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Decorators\UserAdminDecorator;

class UserAdminController extends ModelAdminController
{
	public function __construct(UserAdminDecorator $user)
	{
		parent::__construct($user);
	}
}
