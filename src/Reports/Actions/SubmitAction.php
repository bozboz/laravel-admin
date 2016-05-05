<?php

namespace Bozboz\Admin\Reports\Actions;

class SubmitAction extends Action
{
	protected $attributes = [
		'class' => 'btn-success',
		'name' => null,
		'value' => null,
		'icon' => 'fa fa-save',
	];

	public function __construct($attributes = [])
	{
		parent::__construct(null, null, $attributes);
	}

	public function getView()
	{
		return 'admin::report-actions.submit';
	}

	public function getViewData()
	{
		return $this->getAttributes() + $this->defaults;
	}
}

