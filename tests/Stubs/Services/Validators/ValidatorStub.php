<?php namespace Bozboz\Admin\Tests\Stubs\Services\Validators;

use Bozboz\Admin\Services\Validators\Validator;

class ValidatorStub extends Validator
{
	public function __construct($rules)
	{
		$this->rules = $rules;
	}
}
