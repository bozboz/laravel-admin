<?php namespace Bozboz\Admin\Fields;

class HTMLEditorField extends TextareaField
{
	public function getInput()
	{
		$this->attributes['class'] .= ' html-editor';
		return parent::getInput();
	}
}
