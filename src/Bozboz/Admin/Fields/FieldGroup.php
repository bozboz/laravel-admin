<?php namespace Bozboz\Admin\Fields;

use Bozboz\Admin\Fields\Field;

class FieldGroup extends Field
{
	protected $legend;
	protected $fields;
	protected $view;

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
		$view = $this->view ?: 'admin::fields.field-group';
		return \View::make($view)->with([
			'legend' => $this->legend,
			'fields' => $this->fields,
			'attributes' => $this->attributes,
		]);
	}
}
