<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use InvalidArgumentException;

class SelectField extends Field
{
	public function getInput()
	{
		if (!isset($this->options)) {
			throw new InvalidArgumentException('You must define an "options" key mapping to an array');
		}

		return Form::select($this->name, $this->options, null, $this->attributes);
	}
}
