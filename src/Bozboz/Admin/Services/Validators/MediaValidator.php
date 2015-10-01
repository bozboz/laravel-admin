<?php namespace Bozboz\Admin\Services\Validators;

class MediaValidator extends Validator
{
	protected $storeRules = [
		'filename' => 'required'
	];
}
