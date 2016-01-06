<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\EmailField;
use Bozboz\Admin\Fields\HiddenField;
use Bozboz\Admin\Fields\PasswordField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Permissions\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

	public function modifyListingQuery(Builder $query)
	{
		$query->whereHas('permissions', function($q) {
			$q->where(function($q) {
				$q->where('action', 'admin_login')
				  ->orWhere('action', Permission::WILDCARD);
			});
		})->latest();
	}

	public function getLabel($instance)
	{
		return $instance->getLabel();
	}

	public function getFields($instance)
	{
		return array_filter([
			new TextField('first_name'),
			new TextField('last_name'),
			new EmailField('email'),
			$this->getPasswordFieldForUser($instance)
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
}
