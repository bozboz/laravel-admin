<?php

namespace Bozboz\Admin\Base;

trait SanitisesInputTrait
{
	/**
	 * Sanitise form input, ready for database insertion
	 *
	 * @param  array  $input
	 * @return array
	 */
	public function sanitiseInput($input)
	{
		foreach($this->getNullableAttributes() as $field) {
			if (array_key_exists($field, $input)) {
				$input[$field] = empty($input[$field]) ? null : $input[$field];
			}
		}

		return $input;
	}

	/**
	 * Get nullable properties defined on class, or fall back to an empty array
	 *
	 * @return array
	 */
	protected function getNullableAttributes()
	{
		return property_exists($this, 'nullable') ? (array) $this->nullable : [];
	}
}
