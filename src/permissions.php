<?php

$permissions->define([

	'admin_login'     => 'Bozboz\Permissions\Rules\GlobalRule',

	'edit_profile'    => 'Bozboz\Admin\Permissions\UserRule',

	'view_anything'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_anything' => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_anything'   => 'Bozboz\Permissions\Rules\Rule',
	'delete_anything' => 'Bozboz\Permissions\Rules\Rule',

	'view_pages'  => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_page' => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_page'   => 'Bozboz\Permissions\Rules\Rule',
	'delete_page' => 'Bozboz\Permissions\Rules\Rule',

	'view_users'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_user'  => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_user'    => 'Bozboz\Permissions\Rules\Rule',
	'delete_user'  => 'Bozboz\Permissions\Rules\Rule',

	'view_media'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_media' => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_media'   => 'Bozboz\Permissions\Rules\Rule',
	'delete_media' => 'Bozboz\Permissions\Rules\Rule',

]);
