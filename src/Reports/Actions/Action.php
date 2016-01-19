<?php

namespace Bozboz\Admin\Reports\Actions;

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
	 * @param  Bozboz\Admin\Reports\Row|Bozboz\Admin\Reports\Report  $context
	 * @return boolean
	 */
	public function check($context)
	{
		if ( ! $this->permission) return true;

		return $context->check($this->permission);
	}

	public function getUrl($row = null)
	{
		$params = $row ? ['id' => $row->getId()] : [];

		return action($this->action, $params);
	}

	public function getViewParams($row)
	{
		$params = $this->getAttributes() + $this->defaults;
		$params['url'] = $this->getUrl($row);
		return $params;
	}
}
