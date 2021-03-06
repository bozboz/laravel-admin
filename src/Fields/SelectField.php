<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;
use InvalidArgumentException;

class SelectField extends Field
{
	public function getInput()
	{
		if (!isset($this->options)) {
			throw new InvalidArgumentException('You must define an "options" key mapping to an array');
		}

        $this->class .= ' select2';

        if ($this->radio) {
            return $this->radioFields();
        }

		return Form::select($this->name, $this->options, $this->value, $this->getInputAttributes());
	}

    /**
     * Get the list of attrbitues that shouldn't be added to the input
     * @return array
     */
    protected function getUnsafeAttributes()
    {
        return array_merge(parent::getUnsafeAttributes(), [
            'options', 'value'
        ]);
    }

    private function radioFields()
    {
        return collect($this->options)->map(function ($label, $value) {
            return Form::radio("{$this->name}", $value) . ' ' . $label;
        })->implode('<br>');
    }
}
