<?php namespace Bozboz\Admin\FieldMapping\Fields;

use Illuminate\Support\Facades\Form;

class TextareaField extends Field
{
	public function getInput($params = array())
	{
		return Form::textarea($this->get('name'), null, $params);
	}
}
