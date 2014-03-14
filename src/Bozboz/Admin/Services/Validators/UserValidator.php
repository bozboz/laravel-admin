<?php namespace Bozboz\Admin\Services\Validators;

class UserValidator extends Validator
{
	protected $rules = array(
		'username' => 'required',
		'email' => 'required|email|unique:users',
		'name' => 'required'
	);

	protected $storeRules = array(
		'password' => 'required|min:8',
	);
}
