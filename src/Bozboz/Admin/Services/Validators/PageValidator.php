<?php namespace Bozboz\Admin\Services\Validators;

class PageValidator extends Validator
{
	protected $rules = array(
		'title' => 'required',
		'slug' => 'required|unique:pages',
		'description' => 'required'
	);
}
