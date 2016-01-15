<?php

namespace Bozboz\Admin\Reports;

use Illuminate\Support\Fluent;

abstract class Action extends Fluent
{
	abstract public function getView();

	public function getViewParams($row)
	{
		$params = $this->getAttributes();
		$aprams['row'] = $row;
		return $params;
	}
}