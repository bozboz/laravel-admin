<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class HTMLEditorField extends TextareaField
{
	const HTML_CLASS = 'html-editor';

	public function getInput($params = array())
	{
		$params['class'] = $this->addClassFromParams($params);
		return parent::getInput($params);
	}

	protected function addClassFromParams(array $params)
	{
		if (empty($params['class'])) {
			$classes = array();
		} else {
			$classes = explode(' ', $params['class']);
		}
		$classes[] = static::HTML_CLASS;
		return implode(' ', $classes);
	}
}
