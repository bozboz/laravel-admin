<?php namespace Bozboz\Admin\Fields;

use Illuminate\Html\FormFacade as Form;

class EmailField extends Field
{
	public function getInput()
	{
		return Form::email($this->get('name'), $this->get('value'), $this->attributes);
	}
}
