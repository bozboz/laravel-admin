<?php namespace Bozboz\Admin\FieldMapping;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;

class Field extends Fluent
{
	public function getLabel($params = array())
	{
		return Form::label($this->get('name'), $this->get('label'), $params);
	}

	abstract public function getInput()
	{
		$method = $this->get('method');
		$args = $this->get('args');
		return call_user_func_array('Form', $method, $args);

		$type = $this->get('type');
		$params = array_merge((array)$this->get('params'), $params);

		$args = $params;
		array_unshift($args, $this->get('name'));

		return call_user_func_array(array('Form', $type), $args), 

		if($type == 'password' OR $type == 'select')
			return Form::$type($this->get('name'), $params);
		else
			return Form::$type($this->get('name'), null, $params);
	}

	public function getErrors(MessageBag $errors)
	{
		if($errors->first($this->get('name')))
			return '<p><strong>' . $errors->first($this->get('name')) . '</</p>';
	}
}
