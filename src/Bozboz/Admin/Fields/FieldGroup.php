<?php namespace Bozboz\Admin\Fields;

use View;
use Bozboz\Admin\Fields\Field;

class FieldGroup extends Field
{
	protected $legend;
	protected $fields;
	protected $view = 'admin::fields.field-group';

	public function __construct($name, $fields, $attributes=[])
	{
		$this->legend = $name;
		$this->fields = $fields;
		$this->attributes = $attributes;
	}

	public function getJavascript()
	{
		$javascript = [];
		foreach ($this->fields as $field) {
			$javascript[] = $field->getJavascript();
		}
		return implode(PHP_EOL, array_filter($javascript));
	}

	public function getInput()
	{
		return View::make($this->view)->with([
			'legend' => $this->legend,
			'fields' => $this->fields,
			'attributes' => $this->attributes,
		]);
	}

	public function render($errors)
	{
		return '<div class="form-group">'.$this->getInput().'</div>';
	}
}
