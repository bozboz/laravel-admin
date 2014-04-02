<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use InvalidArgumentException;

class FileField extends Field
{
	public function getInput($params = array())
	{
		return Form::file($this->get('name'), $params);
	}
}

