<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class EmailField extends Field
{
	public function getInput($params = array())
	{
		return Form::email($this->get('name'), $this->get('value'), $params);
	}
}
