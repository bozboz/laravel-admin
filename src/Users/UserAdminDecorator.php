<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\BelongsToField;
use Bozboz\Admin\Fields\EmailField;
use Bozboz\Admin\Fields\HiddenField;
use Bozboz\Admin\Fields\PasswordField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Users\RoleAdminDecorator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserAdminDecorator extends ModelAdminDecorator
{
	public function __construct(UserInterface $user, RoleAdminDecorator $roles)
	{
		$this->roles = $roles;

		parent::__construct($user);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id,
			'email' => $instance->email,
			'role' => $instance->role ? link_to_route('admin.permissions.index', $this->roles->getLabel($instance->role), ['role' => $instance->role->id]) : null,
		);
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->hasPermission('admin_login')->latest();
	}

	public function getLabel($instance)
	{
		return $instance->first_name ? $instance->first_name . ' ' . $instance->last_name : $instance->email;
	}

	public function getFields($instance)
	{
		return array_filter([
			new TextField('first_name'),
			new TextField('last_name'),
			new EmailField('email'),
			$this->getPasswordFieldForUser($instance),
			new BelongsToField($this->roles, $instance->role()),
		]);
	}

	protected function getPasswordFieldForUser($user)
	{
		if ( ! $user->exists) {
			$password = new PasswordField('password');
		} elseif ($this->isUserCurrentAuthenticatedUser($user)) {
			$password = new PasswordField('password', ['label' => 'Change Password']);
		} else {
			$password = null;
		}

		return $password;
	}

	protected function isUserCurrentAuthenticatedUser($user)
	{
		return Auth::id() === $user->id;
	}

	public function getListOfAdminUsers()
	{
		return $this->model->orderBy('last_name')->hasPermission('admin_login')->get();
	}
}