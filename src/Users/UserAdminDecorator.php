<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\BelongsToField;
use Bozboz\Admin\Fields\EmailField;
use Bozboz\Admin\Fields\HiddenField;
use Bozboz\Admin\Fields\PasswordField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Admin\Users\RoleAdminDecorator;
use Bozboz\Permissions\Facades\Gate;
use Bozboz\Permissions\RuleStack;
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
			'role' => $instance->role ? (
				Gate::allows('manage_permissions')
					? link_to_route('admin.roles.edit', $this->roles->getLabel($instance->role), $instance->role->id)
					: $this->roles->getLabel($instance->role)
				) : null,
		);
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->hasPermission('admin_login')->orderBy('email');
	}

	public function getLabel($instance)
	{
		return $instance->first_name ? $instance->first_name . ' ' . $instance->last_name : $instance->email;
	}

	public function getFields($instance)
	{
		$availableRoles = $this->getRoleOptions($instance);
		return array_filter([
			new TextField('first_name'),
			new TextField('last_name'),
			new EmailField('email'),
			$this->getPasswordFieldForUser($instance),
			! $availableRoles->isEmpty() && $availableRoles->keys()->contains($instance->role_id)
				? new SelectField($instance->role()->getForeignKey(), [
					'options' => $availableRoles,
					'label' => 'Role'
				])
				: null,
		]);
	}

	protected function getRoleOptions($instance)
	{
		return $this->roles->getListingModelsNoLimit()->filter(function($role) {
			$stack = new RuleStack;

			$stack->add('edit_anything');
			$stack->add('assign_roles', $role);

			return $stack->isAllowed();
		})->pluck('name', 'id');
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

	public function getListingFilters()
	{
		return [
			new ArrayListingFilter('role', $this->getListOfRoles(), 'role_id'),
		];
	}

	protected function getListOfRoles()
	{
		return $this->roles->getListingModelsNoLimit()->prepend([
			'id' => null,
			'name' => 'All',
		])->lists('name', 'id');
	}
}
