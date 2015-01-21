<?php namespace Bozboz\Admin\Models;

use Eloquent;

abstract class Base extends Eloquent
{
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
