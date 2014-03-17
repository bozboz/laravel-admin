<?php namespace Bozboz\Admin\Models;

use Eloquent;

abstract class Base extends Eloquent
{
	/**
	 * Get the Validator used by this model.
	 *
	 * @return Validator
	 */
	abstract public function getValidator();

	/**
	 * Get the value of the primary key
	 *
	 * @return mixed
	 */
	public function getId()
	{
		$primaryKey = $this->getKeyName();
		return $this->$primaryKey;
	}
}