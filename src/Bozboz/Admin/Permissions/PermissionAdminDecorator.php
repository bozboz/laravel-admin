<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Decorators\UserAdminDecorator;
use Bozboz\Admin\Fields\BelongsToField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Models\User;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Permissions\Handler;
use Illuminate\Database\Eloquent\Builder;

class PermissionAdminDecorator extends ModelAdminDecorator
{
	protected $permissions;

	protected $users;

	public function __construct(Permission $permission, Handler $permissions, UserAdminDecorator $users)
	{
		$this->permissions = $permissions;

		$this->users = $users;

		parent::__construct($permission);
	}

	public function getLabel($instance)
	{
		$label = $instance->action;

		if ($instance->param) {
			$label .= ' [' . $instance->param . ']';
		}

		return $label;
	}

	protected function getActions()
	{
		$rules = array_keys($this->permissions->dump());

		array_unshift($rules, Permission::WILDCARD);

		return array_combine($rules, $rules);
	}

	public function getFields($instance)
	{
		return [
			new SelectField('action', [
				'options' => $this->getActions(),
				'class' => 'select2'
			]),
			new TextField('param'),
			new BelongsToField($this->users, $instance->user()),
		];
	}

	public function getColumns($instance)
	{
		return [
			'Action' => $instance->action,
			'Param' => $instance->param ?: '-',
			'User' => link_to_action('admin.users.edit', $this->users->getLabel($instance->user), [$instance->user->id])
		];
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->with('user')
			->orderBy('user_id')
			->orderBy('action')
			->orderBy('param');
	}

	public function getListingFilters()
	{
		return [
			new ArrayListingFilter('user', $this->getListOfAdminUsers(), 'user_id')
		];
	}

	protected function getListOfAdminUsers()
	{
		$users = $this->users->getListOfAdminUsers()->keyBy('id')->map(function($user) {
			return $this->users->getLabel($user);
		});

		$users->prepend('All', '');

		return $users;
	}
}
