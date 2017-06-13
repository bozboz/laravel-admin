<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;

class CheckboxesField extends Field
{
	public function getInput($params = array())
	{
		$html = sprintf('<input name="%1$s" type="hidden" id="%1$s">', $this->get('name'));

		foreach($this->options as $option) {
			$id = $this->get('name') . '[' . $option->getKey() . ']';
			$checkbox = Form::checkbox(
				$this->get('name') . '[]',
				$option->getKey(),
				null,
				array('id' => $id)
			);

			$html .= '<label class="checkbox">' . $checkbox . ' ' . $option->name . '</label>';
		}

		if ($this->options->isEmpty()) {
			$html .= 'No options available';
		}

		return $html;
	}

	/**
	 * Get the list of attrbitues that shouldn't be added to the input
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return array_merge(parent::getUnsafeAttributes(), [
			'options',
		]);
	}
}
