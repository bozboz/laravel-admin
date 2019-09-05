<?php namespace Bozboz\Admin\Fields;

class HTMLEditorField extends TextareaField
{
	protected $enableVue = true;

	public function getInput()
	{
		$this->attributes['class'] .= ' tinymce';
		$props = [];
		if (config('admin.tinymce.formats')) {
			$props[] = ':formats=\''.json_encode(config('admin.tinymce.formats')).'\'';
		}
		if (config('admin.tinymce.style-formats')) {
			$props[] = ':style-formats=\''.json_encode(config('admin.tinymce.style-formats')).'\'';
		}
		if (config('admin.tinymce.content-style')) {
			$props[] = 'content-style="'.config('admin.tinymce.content-style').'"';
		}
		return '<tinymce-editor replace="'.$this->get('name').'" '.implode(' ', $props).'></tinymce-editor>'.parent::getInput();
	}
}
