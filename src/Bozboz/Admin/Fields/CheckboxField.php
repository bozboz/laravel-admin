<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;

class CheckboxField extends Field
{
	public function getInput()
	{
		return '<input type="hidden" name="' . $this->get('name') . '" value="">'
		     . Form::checkbox($this->get('name'), 1, $this->getCheckedState());
	}

	/**
	 * Get the checked state, or fall back to session/model data if nothing is
	 * set (null returned)
	 *
	 * @return boolean|null
	 */
	protected function getCheckedState()
	{
		return $this->get('checked');
	}
}
