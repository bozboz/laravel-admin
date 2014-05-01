<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class TextareaField extends Field
{
	public function getInput()
	{
		return Form::textarea($this->get('name'), null, $this->attributes);
	}
}
