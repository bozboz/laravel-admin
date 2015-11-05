<?php

namespace Bozboz\Admin\Permissions;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
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

	public function __construct(Permission $permission, Handler $permissions)
	{
		$this->permissions = $permissions;

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
			new BelongsToField(app('Bozboz\Admin\Decorators\UserAdminDecorator'), $instance->user()),
		];
	}

	public function getColumns($instance)
	{
		return [
			'Action' => $instance->action,
			'Param' => $instance->param ?: '-',
			'User' => link_to_action('admin.users.edit', $instance->user->first_name, [$instance->user->id])
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
			new ArrayListingFilter('user_id', ['' => 'All'] + $this->model->user()->getModel()->lists('first_name', 'id'))
		];
	}
}
