<?php

namespace Bozboz\Admin\Reports\Actions;

use Illuminate\Support\Fluent;

abstract class Action extends Fluent
{
	abstract public function getView();

	public function getUrl($row)
	{
		return action($this->action, ['id' => $row->getId()]);
	}

	public function getViewParams($row)
	{
		$params = $this->getAttributes();
		$params['row'] = $row;
		$params['url'] = $this->getUrl($row);
		return $params;
	}
}
