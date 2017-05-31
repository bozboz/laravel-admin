<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class EmailField extends Field
{
	public function getInput()
	{
		return Form::email($this->get('name'), $this->get('value'), $this->getInputAttributes());
	}
}
