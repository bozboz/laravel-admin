<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class CheckboxesField extends Field
{
	public function getInput($params = array())
	{
		$html = sprintf('<input name="%1$s" type="hidden" id="%1$s">', $this->get('name'));

		foreach($this->attributes['options'] as $key => $option) {
			$id = $this->get('name') . '[' . $key . ']';
			$checkbox = Form::checkbox(
				$this->get('name') . '[]',
				$key,
				null,
				array('id' => $id)
			);

			$html .= '<label class="checkbox">' . $checkbox . ' <span>' . $option . '</span></label>';
		}

		return $html;
	}
}
