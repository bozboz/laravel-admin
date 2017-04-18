<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Permissions\Permission;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Permissions\Handler;
use Bozboz\Permissions\Rules\GlobalRule;
use Illuminate\Database\Eloquent\Builder;

class RoleAdminDecorator extends ModelAdminDecorator
{
	protected $permissions;

	public function __construct(Role $model, Handler $permissions)
	{
		parent::__construct($model);
		$this->permissions = $permissions;
	}

	public function getColumns($instance)
	{
		return [
			'Name' => str_replace(' ', '&nbsp;', $instance->name),
			'Permissions' => $instance->permissions->sortBy('action')->map(function($permission) {
				return '<span class="badge"> '
					. $permission->action . ($permission->param ? ":{$permission->param}" : '')
				. '</span>';
			})->implode(' '),
		];
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->with(['permissions' => function($query) {
			$query->orderBy('action');
		}])->orderBy('name');
	}

	public function getFields($instance)
	{
		return [
			new TextField('name'),
			new PermissionsField($this->getActions()),
		];
	}

	public function getListingFilters()
	{
		return [
			new ArrayListingFilter('action', ['' => 'All'] + $this->getActions(), function($query, $value) {
				$query->whereHas('permissions', function($query) use ($value) {
					$query->whereAction($value);
				});
			})
		];
	}

	public function getLabel($instance)
	{
		return $instance->name;
	}

	protected function getActions()
	{
		$rules = $this->permissions->dump();

		$rules = [Permission::WILDCARD => GlobalRule::class] + $rules;

		return $rules;
	}
}