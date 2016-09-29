<?php namespace Bozboz\Admin\Services\Validators;

class UserValidator extends Validator
{
	protected $rules = array(
		'email' => 'required|email|unique:users,email,{id}',
		'first_name' => 'required',
		'last_name' => 'required',
		'role' => 'required',
	);

	protected $storeRules = array(
		'password' => 'required|min:8',
	);
}