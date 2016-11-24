<?php

namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Permissions\Permission;
use Bozboz\Admin\Users\RoleAdminDecorator;

class UserRoleAdminController extends ModelAdminController
{
	public function __construct(RoleAdminDecorator $decorator)
	{
		parent::__construct($decorator);
	}

    protected function save($modelInstance, $input)
    {
        parent::save($modelInstance, $input);

        $modelInstance->permissions()->delete();

        collect($input['permission_options'])->each(function($permission, $action) use ($modelInstance) {
            if (array_key_exists('exists', $permission)) {
                collect(explode(',', $permission['params']))->each(function($param) use ($modelInstance, $action) {
                    $modelInstance->permissions()->create([
                        'action' => $action == '&#42;' ? Permission::WILDCARD : $action,
                        'param' => trim($param) ?: null,
                    ]);
                });
            }
        });
    }
}