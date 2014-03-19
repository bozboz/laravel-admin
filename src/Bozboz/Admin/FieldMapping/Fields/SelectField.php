<?php namespace Bozboz\Admin\FieldMapping\Fields;

use Illuminate\Support\Facades\Form;
use InvalidArgumentException;

class SelectField extends Field
{
	public function getInput($params = array())
	{
		if (!isset($this->options)) {
			throw new InvalidArgumentException('You must define an "options" key mapping to an array');
		}

		return Form::select($this->get('name'), $this->options);
	}
}
