<?php namespace Bozboz\Admin\Decorators;

use Illuminate\Database\Eloquent\Builder;

use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Fields\EmailField;
use Bozboz\Admin\Fields\HiddenField;
use Bozboz\Admin\Fields\PasswordField;
use Bozboz\Admin\Models\User;
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
		$query->hasPermission('admin_login')->latest();
	}

	public function getLabel($instance)
	{
		return $instance->first_name . ' ' . $instance->last_name;
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

	public function getListOfAdminUsers()
	{
		return $this->model->orderBy('last_name')->hasPermission('admin_login')->get();
	}
}
