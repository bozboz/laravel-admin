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
		$this->fields = $this->getFilteredCollectionOfFields($fields);
		$this->attributes = $attributes;
	}

	private function getFilteredCollectionOfFields($fields)
	{
		if (is_array($fields)) $fields = collect($fields);

		return $fields->filter();
	}

	public function getJavascript()
	{
		return $this->fields->map(function($field) {
			return $field->getJavascript();
		})->implode(PHP_EOL);
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
