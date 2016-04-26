<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Str;

class DateField extends Field
{
	public function __construct($attributesOrName, $attributes = array())
	{
		parent::__construct($attributesOrName, $attributes);
		$this->normalisedName = str_replace('-', '', str_slug($this->name));
	}

	protected function getJsClass()
	{
		return 'js-datepicker';
	}

	public function getInput()
	{
		return Form::text($this->name, null, [
			'id' => $this->normalisedName,
			'class' => 'form-control form-control--small '.$this->getJsClass().' '.$this->class,
		]);
	}

	public function getJavascript()
	{
		$jsonConfig = json_encode($this->options ?: []);

		return <<<JAVASCRIPT
			window.datePicker = window.datePicker || {};
			window.datePicker.{$this->normalisedName} = $jsonConfig;
JAVASCRIPT;
	}}
