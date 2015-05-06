<?php namespace Bozboz\Admin\Fields;

use View;
use Illuminate\Support\Facades\Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\ViewErrorBag;

abstract class Field extends Fluent
{
	public function __construct($attributesOrName, $attributes = array())
	{
		if (!is_array($attributesOrName)) {
			$attributes['name'] = $attributesOrName;
		} else {
			$attributes = $attributesOrName;
		}

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
		if ($this->name && $errors->first($this->name)) {
			return '<p><strong>' . $errors->first($this->get('name')) . '</strong></p>';
		}
	}

	public function getJavascript()
	{
		return null;
	}
	
	public function render()
	{
		return View::make('admin::fields.field')->with(['field' => $this]);
	}
}
