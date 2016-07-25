<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Permissions\PermissionAdminDecorator;

class RoleAdminDecorator extends ModelAdminDecorator
{
	protected $permissions;

	public function __construct(Role $model)
	{
		parent::__construct($model);
	}

	public function getFields($instance)
	{
		return [
			new TextField('name'),
		];
	}

	public function getLabel($instance)
	{
		return $instance->name;
	}
}