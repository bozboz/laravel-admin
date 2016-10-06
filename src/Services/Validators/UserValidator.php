<?php

namespace Bozboz\Admin\Services\Validators;

class UserValidator extends Validator
{
	protected $rules = [
		'email' => 'required|email|unique:users,email,{id}',
		'first_name' => 'required',
		'last_name' => 'required',
    ];

    protected $storeRules = [
		'role_id' => 'required',
		'password' => 'required|min:8',
	];
}