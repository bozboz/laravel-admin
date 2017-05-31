<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class CheckboxField extends Field
{
	public function getInput()
	{
		$this->class = str_replace('form-control', '', $this->class);
		return '<input type="hidden" name="' . $this->get('name') . '" value="">'
		     . Form::checkbox($this->get('name'), 1, $this->getCheckedState(), $this->getInputAttributes());
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

	/**
	 * Get the list of attrbitues that shouldn't be added to the input
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return array_merge(parent::getUnsafeAttributes(), [
			'checked',
		]);
	}
}
