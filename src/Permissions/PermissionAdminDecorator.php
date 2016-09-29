<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\BelongsToField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Admin\Users\RoleAdminDecorator;
use Bozboz\Admin\Users\User;
use Bozboz\Permissions\Handler;
use Illuminate\Database\Eloquent\Builder;

class PermissionAdminDecorator extends ModelAdminDecorator
{
	protected $permissions;

	protected $roles;

	public function __construct(Permission $permission, Handler $permissions, RoleAdminDecorator $roles)
	{
		$this->permissions = $permissions;

		$this->roles = $roles;

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
			new BelongsToField($this->roles, $instance->role()),
		];
	}

	public function getColumns($instance)
	{
		return [
			'Action' => $instance->action,
			'Param' => $instance->param ?: '-',
			'Role' => $instance->role ? link_to_route('admin.roles.edit', $this->roles->getLabel($instance->role), [$instance->role->id]) : null,
		];
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->with('role')
			->orderBy('user_id')
			->orderBy('action')
			->orderBy('param');
	}

	public function getListingFilters()
	{
		return [
			new ArrayListingFilter('role', $this->getListOfRoles(), 'role_id')
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
