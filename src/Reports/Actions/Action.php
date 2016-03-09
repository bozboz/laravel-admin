<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\ChecksPermissions;
use Illuminate\Support\Fluent;

abstract class Action extends Fluent
{
	protected $defaults = [
		'warn' => null,
		'class' => 'btn-default',
		'icon' => '',
		'label' => 'Unknown',
	];

	protected $instance;

	abstract public function getView();

	public function __construct($action, $permission = null, $attributes = [])
	{
		$this->action = $action;

		$attributes['permission'] = $permission;

		parent::__construct($attributes);
	}

	/**
	 * Request that a instance can assert the provided permission
	 *
	 * @param  $instance
	 * @return boolean
	 */
	public function check($instance = null)
	{
		if ( ! $this->permission) return true;

		return call_user_func($this->permission, $instance);
	}

	public function setInstance($instance)
	{
		$this->instance = $instance;
	}

	/**
	 * Generate a URL based on a defined route action
	 *
	 * @return string
	 */
	public function getUrl()
	{
		if (is_array($this->action)) {
			$action = $this->action[0];
			$params = array_slice($this->action, 1);
		} else {
			$action = $this->action;
			$params = [];
		}

		if ($this->instance) {
			array_push($params, $this->instance->id);
		}

		return action($action, array_filter($params));
	}

	/**
	 * Get the parameters to inject into a view
	 *
	 * @return array
	 */
	public function getViewData()
	{
		$attributes = $this->getAttributes() + $this->defaults;
		$attributes['url'] = $this->getUrl();
		return $attributes;
	}
}
