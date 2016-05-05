<?php

namespace Bozboz\Admin\Reports\Actions;

class SubmitAction extends Action
{
	protected $attributes = [
		'class' => 'btn-success',
		'name' => null,
		'value' => null,
		'icon' => '',
	];

	public function getView()
	{
		return 'admin::report-actions.submit';
	}
}
