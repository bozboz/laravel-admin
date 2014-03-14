<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\User;

class UserAdminDecorator extends ModelAdminDecorator
{
	protected static $fields = array(
		'username' => array('type' => 'text'),
		'name' => array('type' => 'text'),
		'email' => array('type' => 'email'),
		'password' => array('type' => 'password')
	);

	public function __construct(User $user)
	{
		parent::__construct($user);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('name');
	}
}
