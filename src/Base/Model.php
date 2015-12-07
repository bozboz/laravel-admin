<?php

namespace Bozboz\Admin\Base;

use Bozboz\Admin\Services\Validators\EmptyValidator;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent implements ModelInterface
{
	use SanitisesInputTrait;

	/**
	 * @var array
	 */
	protected $nullable = [];

	/**
	 * Return an empty validator as a default implementation of method
	 *
	 * @return Bozboz\Admin\Services\Validators\EmptyValidator
	 */
	public function getValidator()
	{
		return new EmptyValidator;
	}
}
