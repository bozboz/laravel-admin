<?php

namespace Bozboz\Admin\Models;

interface BaseInterface
{
	/**
	 * Get the Validator used by this model.
	 *
	 * @return Bozboz\Admin\Services\Validators\Validator
	 */
	public function getValidator();

	/**
	 * Sanitise form input, ready for database insertion
	 *
	 * @param  array  $input
	 * @return array
	 */
	public function sanitiseInput($input);

}
