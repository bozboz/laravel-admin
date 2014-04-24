<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\User;

class UserAdminDecorator extends ModelAdminDecorator
{
	public function __construct(User $user)
	{
		parent::__construct($user);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id,
			'email' => $instance->email
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('name');
	}

	public function getFields()
	{
		return array(
			new \Bozboz\Admin\Fields\TextField(array('name' => 'name')),
			new \Bozboz\Admin\Fields\TextField(array('name' => 'username')),
			new \Bozboz\Admin\Fields\EmailField(array('name' => 'email')),
			new \Bozboz\Admin\Fields\PasswordField(array('name' => 'password'))
		);
	}
}
