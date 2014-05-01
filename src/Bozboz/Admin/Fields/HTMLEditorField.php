<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class HTMLEditorField extends TextareaField
{
	public function getInput()
	{
		$this->attributes['class'] .= ' html-editor';
		return parent::getInput();
	}
}
