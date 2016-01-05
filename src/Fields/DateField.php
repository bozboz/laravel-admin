<?php namespace Bozboz\Admin\Fields;

use Form;
use Illuminate\Support\Str;

class DateField extends Field
{
	protected function getJsClass()
	{
		return 'js-datepicker';
	}

	public function getInput()
	{
		return Form::text($this->name, null, [
			'id' => $this->name,
			'class' => 'form-control form-control--small '.$this->getJsClass()
		]);
	}

	public function getJavascript()
	{
		$jsonConfig = json_encode($this->options ?: []);

		return <<<JAVASCRIPT
			window.datePicker = window.datePicker || {};
			window.datePicker.{$this->name} = $jsonConfig;
JAVASCRIPT;
	}}
