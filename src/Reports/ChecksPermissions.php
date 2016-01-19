<?php

namespace Bozboz\Admin\Reports;

interface ChecksPermissions
{
	/**
	 * Check if assertion is true
	 *
	 * @param  callable  $assertion
	 * @return boolean
	 */
	public function check(callable $assertion);
}
