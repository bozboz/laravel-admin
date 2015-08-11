<?php namespace Bozboz\Admin\Models;

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
	 * Get the Validator used by this model.
	 *
	 * @return Validator
	 */
	abstract public function getValidator();
}
