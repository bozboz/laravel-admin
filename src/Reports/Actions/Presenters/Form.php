<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Form extends Presenter
{
	private $action;
	private $button;

	public function __construct($action, $label, $icon = null, $attributes = [], $formAttributes = [])
	{
		$this->button = new Button($label, $icon, $attributes);
		$this->action = $action;

		parent::__construct($formAttributes);
	}

	public function getView()
	{
		return 'admin::report-actions.form';
	}

	public function getViewData()
	{
		return [
			'attributes' => $this->compileFormAttributes(),
			'button' => $this->button,
		];
	}

	protected function compileFormAttributes()
	{
		return [
			'class' => 'inline-form',
			'method' => 'POST',
			'url' => $this->getUrl(),
		] + $this->attributes;
	}

	protected function getUrl()
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
