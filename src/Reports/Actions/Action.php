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

	public function __construct($action, $permission = null, $params = [])
	{
		$this->action = $action;

		$params['permission'] = $permission;

		parent::__construct($params);
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
	public function getUrl($row = null)
	{
		$params = $row ? ['id' => $row->getId()] : [];

		return action($this->action, $params);
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
