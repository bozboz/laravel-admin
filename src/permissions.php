<?php

$permissions->define([

	'login'          => 'Bozboz\Permissions\Rules\GlobalRule',

	'edit_profile'    => 'Bozboz\Admin\Permissions\UserRule',

	'view_anything'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'create_anything' => 'Bozboz\Permissions\Rules\GlobalRule',
	'edit_anything'   => 'Bozboz\Permissions\Rules\GlobalRule',
	'delete_anything' => 'Bozboz\Permissions\Rules\GlobalRule',

]);
