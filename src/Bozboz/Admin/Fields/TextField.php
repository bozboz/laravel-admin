<?php namespace Bozboz\Admin\Fields;

use Illuminate\Html\FormFacade as Form;

class TextField extends Field
{
	public function getInput()
	{
		return Form::text($this->get('name'), null, $this->attributes);
	}
}
