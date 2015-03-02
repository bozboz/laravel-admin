<?php namespace Bozboz\MediaLibrary\Validators;

use Bozboz\Admin\Services\Validators\Validator;

class MediaValidator extends Validator
{
	protected $rules = [
		'filename' => 'required'
	];

	protected $editRules = [
		'filename' => ''
	];
}
