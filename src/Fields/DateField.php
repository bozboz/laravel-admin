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
		$this->class .= ' form-control form-control--small '.$this->getJsClass();
		$this->id = $this->normalisedName;

		return Form::text($this->name, $this->value, $this->getInputAttributes());
	}

	public function getJavascript()
	{
		$jsonConfig = json_encode($this->options ?: []);

		return <<<JAVASCRIPT
			window.datePicker = window.datePicker || {};
			window.datePicker.{$this->normalisedName} = $jsonConfig;
JAVASCRIPT;
	}


	/**
	 * Get the list of attrbitues that shouldn't be added to the input
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return array_merge(parent::getUnsafeAttributes(), [
			'normalisedName',
		]);
	}
}
