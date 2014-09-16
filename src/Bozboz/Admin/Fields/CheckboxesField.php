<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use Illuminate\Database\Eloquent\Model;

class CheckboxesField extends Field
{
	public function getInput($params = array())
	{
		$html = Form::hidden($this->get('name'), '');

		foreach($this->options as $option) {
			$id = $this->get('name') . '[' . $option->id . ']';
			$checkbox = Form::checkbox(
				$this->get('name') . '[]',
				$option->id,
				null,
				array('id' => $id)
			);

			$html .= '<label class="checkbox">' . $checkbox . ' ' . $option->name . '</label>';
		}

		return $html;
	}
}
