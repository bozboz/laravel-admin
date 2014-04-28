<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use InvalidArgumentException;

class FileField extends Field
{
	public function getInput($params = array())
	{
		$html = '';
		if ($filename = Form::getValueAttribute('filename')) {
			$html .= sprintf('<img src="/images/thumb/media/image/%s" style="margin-bottom: 5px; display: block">', $filename);
		}
		$html .= Form::file($this->get('name'), $params);
		return $html;
	}
}

