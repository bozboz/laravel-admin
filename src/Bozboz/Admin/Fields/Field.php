<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\ViewErrorBag;

abstract class Field extends Fluent
{
	public function __construct(array $attributes)
	{
		foreach ($attributes as $key => $attribute) {
			$this->attributes[$key] = $attribute;
		}
	}

	protected $attributes = array(
		'class' => 'form-control'
	);

	abstract public function getInput();

	public function getLabel()
	{
		return Form::label($this->get('name'), $this->get('label'));
	}

	public function getErrors(ViewErrorBag $errors)
	{
		if ($errors->first($this->get('name'))) {
			return '<p><strong>' . $errors->first($this->get('name')) . '</strong></p>';
		}
	}
}
