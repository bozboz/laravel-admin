<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Fluent;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Html\FormFacade as Form;
use View;

abstract class Field extends Fluent
{
	public function __construct($attributesOrName, $attributes = array())
	{
		if (!is_array($attributesOrName)) {
			$attributes['name'] = $attributesOrName;
		} else {
			$attributes = $attributesOrName;
		}

		$attributes += $this->defaultAttributes();

		foreach ($attributes as $key => $attribute) {
			$this->attributes[$key] = $attribute;
		}
	}

	protected function defaultAttributes()
	{
		return [];
	}

	protected $attributes = array(
		'class' => 'form-control'
	);

	abstract public function getInput();

	public function getHelpText()
	{
		if ($this->help_text) {
			return (object) [
				'title' => $this->help_text_title,
				'content' => $this->help_text,
			];
		}
	}

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

	public function render($errors)
	{
		return View::make('admin::fields.field')->with([
			'helpText' => $this->getHelpText(),
			'label' => $this->getLabel(),
			'input' => $this->getInput(),
			'errors' => $this->getErrors($errors),
			'field' => $this,
		]);
	}
}
