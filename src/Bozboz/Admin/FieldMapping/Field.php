<?php namespace Bozboz\Admin\FieldMapping;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;

class Field extends Fluent
{
	public function getLabel()
	{
		return Form::label($this->get('name'), $this->get('label'));
	}

	public function getInput()
	{
		$type = $this->get('type');
		return Form::$type($this->get('name'), $this->get('params'));
	}

	public function getErrors(MessageBag $errors)
	{
		return $errors->first($this->get('name'));
	}
}