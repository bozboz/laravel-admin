<?php namespace Bozboz\Admin\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
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

	/**
	 * Sanitise form input, ready for database insertion
	 *
	 * @param  array  $input
	 * @return array
	 */
	public function sanitiseInput($input)
	{
		foreach($this->nullable as $field) {
			if (array_key_exists($field, $input)) {
				$input[$field] = empty($input[$field]) ? null : $input[$field];
			}
		}

		return $input;
	}
}
