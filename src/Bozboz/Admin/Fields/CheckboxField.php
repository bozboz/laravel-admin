<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class CheckboxField extends Field
{
	public function getInput()
	{
		return Form::hidden($this->get('name'), 0, ['id' => 'hidden_' . $this->get('name')]) . Form::checkbox($this->get('name'), 1);
	}
}
