<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Permissions\Valid;
use Bozboz\Admin\Reports\Actions\Presenters\Button;

class SubmitAction extends Action
{
	public function __construct($label, $icon = null, $attributes = [])
	{
		parent::__construct(
			new Button($label, $icon, $attributes + [
				'type' => 'submit',
				'class' => 'btn-success space-left pull-right',
			])
		);
	}
}
