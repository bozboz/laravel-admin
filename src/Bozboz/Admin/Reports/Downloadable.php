<?php

namespace Bozboz\Admin\Reports;

interface Downloadable
{
	/**
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return array
	 */
	public function getColumnsForCSV($instance);
}
