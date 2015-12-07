<?php

namespace Bozboz\Admin\Reports;

interface Downloadable
{
	/**
	 * @param  Bozboz\Admin\Base\Model  $instance
	 * @return array
	 */
	public function getColumnsForCSV($instance);
}
