<?php

$permissions->define([

	'admin_login'     => 'Bozboz\Permissions\Rules\GlobalRule',

	'edit_profile'    => 'Bozboz\Admin\Permissions\UserRule',

	'view_anything'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_anything' => 'Bozboz\Permissions\Rules\ModelRule',
	'edit_anything'   => 'Bozboz\Permissions\Rules\ModelRule',
	'delete_anything' => 'Bozboz\Permissions\Rules\ModelRule',

	'view_users'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_user'  => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_user'    => 'Bozboz\Permissions\Rules\ModelRule',
	'delete_user'  => 'Bozboz\Permissions\Rules\ModelRule',

	'view_media'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_media' => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_media'   => 'Bozboz\Permissions\Rules\ModelRule',
	'delete_media' => 'Bozboz\Permissions\Rules\ModelRule',

]);
