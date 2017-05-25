<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class TextareaField extends Field
{
    protected $view = 'admin::fields.helptext-before-field';

	public function getInput()
	{
		return Form::textarea($this->get('name'), null, $this->attributes);
	}
}
