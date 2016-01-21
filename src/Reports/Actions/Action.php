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

	abstract public function getView();

	public function __construct($action, $permission = null, $attributes = [])
	{
		$this->action = $action;

		$attributes['permission'] = $permission;

		parent::__construct($attributes);
	}

	/**
	 * Request that a context object can assert the provided permission
	 *
	 * @param  Bozboz\Admin\Reports\ChecksPermissions  $context
	 * @return boolean
	 */
	public function check(ChecksPermissions $context)
	{
		if ( ! $this->permission) return true;

		return $context->check($this->permission);
	}

	/**
	 * Generate a URL based on a defined route action
	 *
	 * @param  Bozboz\Admin\Reports\Row|null  $row
	 * @return string
	 */
	public function getUrl($row)
	{
		if (is_array($this->action)) {
			$action = $this->action[0];
			$params = array_slice($this->action, 1);
		} else {
			$action = $this->action;
			$params = [];
		}

		if (!is_null($row)) {
			array_push($params, $row->getId());
		}

		return action($action, $params);
	}

	/**
	 * Get the parameters to inject into a view
	 *
	 * @param  Bozboz\Admin\Reports\Row  $row
	 * @return array
	 */
	public function getViewData($row)
	{
		$attributes = $this->getAttributes() + $this->defaults;
		$attributes['url'] = $this->getUrl($row);
		return $attributes;
	}
}
