<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Fluent;
use Illuminate\Support\ViewErrorBag;
use View;

abstract class Field extends Fluent
{
	protected $view = 'admin::fields.field';
	protected $enableVue = false;

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

	/**
	 * Get the list of attrbitues that shouldn't be added to the input
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return [
			'hide_if_value_filled',
			'hide_if_value_empty',
			'help_text_title',
			'help_text',
			'label',
		];
	}

	/**
	 * Filter out the attributes not safe for an HTML input
	 * @return array
	 */
	protected function getInputAttributes()
	{
		return array_diff_key(
			$this->attributes ?: [],
			array_combine($this->getUnsafeAttributes(), $this->getUnsafeAttributes())
		);
	}

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
		$dotSyntaxName = str_replace(['][', '[', ']'], ['.', '.', ''], $this->get('name'));
		if ($dotSyntaxName && $errors->first($dotSyntaxName)) {
			return '<p><strong>' . $errors->first($dotSyntaxName) . '</strong></p>';
		}
	}

	public function getJavascript()
	{
		return null;
	}

    public function vueEnabled()
    {
        return $this->enableVue;
    }

	public function render($errors)
	{
		if ($this->shouldHideField()) {
			return (new HiddenField($this->attributes))->render($errors);
		}

		return View::make($this->view)->with([
			'helpText' => $this->getHelpText(),
			'label' => $this->getLabel(),
			'input' => $this->getInput(),
			'errors' => $this->getErrors($errors),
			'field' => $this,
			'enableVue' => $this->enableVue,
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
