<?php namespace Bozboz\Admin\Fields;

use Form;
use View;

class SubField extends Field
{
    protected $identifier;
    protected $decorate;
    protected $originalFieldName;

    public function __construct($identifier, Field $field)
    {
        $this->identifier = $identifier;
        $this->decorate = $field;
        $this->originalFieldName = $field->name;

        $field->name = $identifier . '[' . $field->name . ']';
    }

    public function getInput()
    {
        return $this->decorate->getInput();
    }

    public function getLabel()
    {
        return Form::label($this->originalFieldName, $this->decorate->label);
    }

    public function getErrors(\Illuminate\Support\ViewErrorBag $errors)
    {
        if ($errors->has($this->decorate->name)) {
            return '<p><strong>' . $errors->first($this->decorate->name) . '</strong></p>';
        }
    }
}
