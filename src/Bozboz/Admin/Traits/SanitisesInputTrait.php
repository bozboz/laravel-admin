<?php

namespace Bozboz\Admin\Traits;

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
		foreach($this->nullable as $field) {
			if (array_key_exists($field, $input)) {
				$input[$field] = empty($input[$field]) ? null : $input[$field];
			}
		}

		return $input;
	}
}