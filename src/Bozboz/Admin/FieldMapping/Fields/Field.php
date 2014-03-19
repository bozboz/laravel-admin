<?php namespace Bozboz\Admin\FieldMapping\Fields;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;

abstract class Field extends Fluent
{
	abstract public function getInput($params);

	public function getLabel($params = array())
	{
		return Form::label($this->get('name'), $this->get('label'), $params);
	}

	public function getErrors(MessageBag $errors)
	{
		if ($errors->first($this->get('name'))) {
			return '<p><strong>' . $errors->first($this->get('name')) . '</strong></p>';
		}
	}
}
