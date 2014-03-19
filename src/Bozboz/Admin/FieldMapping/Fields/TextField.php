<?php namespace Bozboz\Admin\FieldMapping\Fields;

use Illuminate\Support\Facades\Form;

class TextField extends Field
{
	public function getInput($params = array())
	{
		return Form::text($this->get('name'), null, $params);
	}
}
