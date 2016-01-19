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

	public function check($row = null)
	{
		if ( ! $this->permission) return true;

		$assertion = $this->permission;

		return $row ? $row->check($assertion) : $assertion($row);
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
