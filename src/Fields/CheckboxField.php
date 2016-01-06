<?php namespace Bozboz\Admin\Fields;

use Illuminate\Html\FormFacade as Form;

class CheckboxField extends Field
{
	public function getInput()
	{
		return '<input type="hidden" name="' . $this->get('name') . '" value="">'
		     . Form::checkbox($this->get('name'), 1, $this->get('checked', false));
	}
}
