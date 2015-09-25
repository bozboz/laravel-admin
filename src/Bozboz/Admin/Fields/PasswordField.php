<?php namespace Bozboz\Admin\Fields;

use Illuminate\Html\FormFacade as Form;

class PasswordField extends Field
{
	public function getInput()
	{
		 return Form::password($this->get('name'), $this->attributes);
	}
}
