<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\EmptyValidator;
use Bozboz\Admin\Traits\SanitisesInputTrait;
use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model implements BaseInterface
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
