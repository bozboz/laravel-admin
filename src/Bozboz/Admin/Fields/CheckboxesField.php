<?php namespace Bozboz\Admin\Fields;

use Illuminate\Support\Facades\Form;
use Illuminate\Database\Eloquent\Model;

class CheckboxesField extends Field
{
	private $factory;

	public function __construct(Model $factory, $params = array())
	{
		$this->factory = $factory;
		parent::__construct($params);
	}

	public function getInput($params = array())
	{
		$html = Form::hidden($this->get('name'));

		$currentValues = $this->getCurrentValues();

		foreach($this->options as $option) {
			$id = $this->get('name') . '[' . $option->id . ']';
			$checkbox = Form::checkbox(
				$this->get('name') . '[]',
				$option->id,
				in_array($option->id, $currentValues),
				array('id' => $id)
			);
			$html .= '<label class="checkbox">' . $checkbox . ' ' . $option->name . '</label>';

		}

		return $html;
	}

	public function getCurrentValues()
	{
		$id = Form::getValueAttribute('id');
		$instance = $this->factory->find($id);

		if (empty($instance)) { //new model
			return array();
		} else {
			$current = $instance->caseStudies;
			return array_pluck($current, 'id');
		}
	}
}
