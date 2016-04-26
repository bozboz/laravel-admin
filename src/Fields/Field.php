<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\ViewErrorBag;
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
		'class' => 'form-control',
		'hide_if_value_filled' => false,
		'hide_if_value_empty' => false,
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

	public function render($errors)
	{
		if ($this->shouldHideField()) {
			return (new HiddenField($this->attributes))->render($errors);
		}

		return View::make('admin::fields.field')->with([
			'label' => $this->getLabel(),
			'input' => $this->getInput(),
			'errors' => $this->getErrors($errors),
			'field' => $this,
		]);
	}

	/**
	 * Determine if field should be hidden, based on hide_if_value_* attributes
	 *
	 * @return boolean
	 */
	protected function shouldHideField()
	{
		$value = Form::getValueAttribute($this->name);

		return ($value && $this->hide_if_value_filled) ||
			(is_null($value) && $this->hide_if_value_empty);
	}
}
