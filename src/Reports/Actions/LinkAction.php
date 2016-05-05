<?php

namespace Bozboz\Admin\Reports\Actions;

class LinkAction extends Action
{
	protected $action;

	public function __construct($action, $permission = null, $attributes = [])
	{
		$this->action = $action;

		parent::__construct($permission, $attributes);
	}

	public function getView()
	{
		return 'admin::report-actions.link';
	}

	/**
	 * Get the parameters to inject into a view
	 *
	 * @return array
	 */
	public function getViewData()
	{
		$attributes = $this->attributes + $this->defaults;
		$attributes['url'] = $this->getUrl();
		return $attributes;
	}

	/**
	 * Generate a URL based on a defined route action
	 *
	 * @return string
	 */
	public function getUrl()
	{
		if (is_array($this->action)) {
			list($action, $params) = $this->action;
			$params = is_array($params) ? $params : [$params];
		} else {
			$action = $this->action;
			$params = [];
		}

		if ($this->instance) {
			array_push($params, $this->instance->id);
		}

		return action($action, array_filter($params));
	}
}
