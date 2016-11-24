<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Permissions\Permission;
use Bozboz\Permissions\Handler;

class RoleAdminDecorator extends ModelAdminDecorator
{
	protected $permissions;

	public function __construct(Role $model, Handler $permissions)
	{
		parent::__construct($model);
		$this->permissions = $permissions;
	}

	public function getFields($instance)
	{
		return [
			new TextField('name'),
			new PermissionsField($this->getActions()),
		];
	}

	public function findInstance($id)
	{
		$instance = parent::findInstance($id);
		if ($instance->permissions->where('action', Permission::WILDCARD)->first()) {
			$instance->permissions->push(new Permission(['action' => '&#42;']));
		}
		return $instance;
	}

	public function getLabel($instance)
	{
		return $instance->name;
	}

	protected function getActions()
	{
		$rules = array_keys($this->permissions->dump());

		array_unshift($rules, Permission::WILDCARD);

		return array_combine($rules, $rules);
	}
}