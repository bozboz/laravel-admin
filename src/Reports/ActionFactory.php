<?php

namespace Bozboz\Admin\Reports;

class ActionFactory
{
	protected $actions;

	public function register($action, $closure)
	{
		$this->actions[$action] = $closure;
	}

	public function __call($method, $args)
	{
		if ( ! array_key_exists($method, $this->actions)) {
			throw new \InvalidArgumentException(
				'No action with the name "' . $method . '" is registered'
			);
		}

		return call_user_func_array($this->actions[$method], $args);
	}
}
