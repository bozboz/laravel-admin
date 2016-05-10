<?php

namespace Bozboz\Admin\Reports\Actions\Presenters\Urls;

class Action implements Contract
{
	private $action;

	public function __construct($action)
	{
		$this->action = $action;
	}

	public function compile($instance)
	{
		if (is_array($this->action)) {
			list($action, $params) = $this->action;
			$params = is_array($params) ? $params : [$params];
		} else {
			$action = $this->action;
			$params = [];
		}

		if ($instance) {
			array_push($params, $instance->id);
		}

		return action($action, array_filter($params));
	}
}
