<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\ChecksPermissions;

abstract class Action
{
	protected $attributes = [];

	protected $defaults = [
		'warn' => null,
		'class' => 'btn-default',
		'icon' => '',
		'label' => 'Unknown',
	];

	protected $instance;

	protected $permission;

	abstract public function getView();

	public function __construct($permission = null, $attributes = [])
	{
		$this->permission = $permission;

		foreach ($attributes as $key => $value) {
			$this->attributes[$key] = $value;
		}
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
		return $this;
	}

	public function getViewData()
	{
		return $this->attributes + $this->defaults;
	}
}
