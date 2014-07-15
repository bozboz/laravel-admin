<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class CheckboxField extends Field
{
	public function getInput()
	{
		return Form::checkbox($this->get('name'), 1);
	}
}
