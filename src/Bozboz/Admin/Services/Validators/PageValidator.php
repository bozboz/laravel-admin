<?php namespace Bozboz\Admin\Services\Validators;

class PageValidator extends Validator
{
	protected $rules = array(
		'title' => 'required',
		'slug' => 'unique:pages'
	);

	protected $storeRules = array(
		'password' => 'required'
	);
}
