<?php

namespace Bozboz\Admin\Services\Validators;

use Bozboz\Admin\Services\Validators\Validator;

class EmptyValidator extends Validator
{
	protected function passes($attributes, $rules)
	{
		return true;
	}
}
