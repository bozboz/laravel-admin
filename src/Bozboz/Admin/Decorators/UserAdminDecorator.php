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

	public function getListingModels()
	{
		return $this->model->where('is_admin', true)->get();
	}

	public function getLabel($instance)
	{
		return $instance->first_name . ' ' . $instance->last_name;
	}

	public function getFields($instance)
	{
		return array_filter([
			new \Bozboz\Admin\Fields\TextField('first_name'),
			new \Bozboz\Admin\Fields\TextField('last_name'),
			new \Bozboz\Admin\Fields\EmailField('email'),
			$instance->exists ? null : new \Bozboz\Admin\Fields\PasswordField('password'),
			new \Bozboz\Admin\Fields\HiddenField('is_admin', true)
		]);
	}
}
