<?php namespace Bozboz\Admin\Fields;

use Illuminate\Html\FormFacade as Form;
use InvalidArgumentException;

class SelectField extends Field
{
	public function getInput()
	{
		if (!isset($this->options)) {
			throw new InvalidArgumentException('You must define an "options" key mapping to an array');
		}

		$attributes = $this->attributes;
		unset($attributes['options']);

		return Form::select($this->name, $this->options, null, $attributes);
	}
}
