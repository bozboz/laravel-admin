<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class CheckboxesField extends Field
{
	public function getInput($params = array())
	{
		$html = sprintf('<input name="%1$s" type="hidden" id="%1$s">', $this->get('name'));

		foreach($this->options as $option) {
			$id = $this->get('name') . '[' . $option->id . ']';
			$checkbox = Form::checkbox(
				$this->get('name') . '[]',
				$option->getKey(),
				null,
				array('id' => $id)
			);

			$html .= '<label class="checkbox">' . $checkbox . ' ' . $option->getLabel() . '</label>';
		}

		return $html;
	}
}
