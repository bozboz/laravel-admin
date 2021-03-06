<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class HiddenField extends Field
{
	public function __construct($attributesOrName, $default = null)
	{
		parent::__construct($attributesOrName, ['default' => $default]);
	}

	public function getInput()
	{
		return Form::hidden($this->get('name'), $this->get('default'));
	}

	public function getLabel()
	{
		// empty
	}

	public function render($errors)
	{
		return $this->getInput();
	}
}
