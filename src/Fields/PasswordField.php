<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class PasswordField extends Field
{
	public function getInput()
	{
		 return Form::password($this->get('name'), $this->getInputAttributes());
	}
}
