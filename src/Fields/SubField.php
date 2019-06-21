<?php namespace Bozboz\Admin\Fields;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\ViewErrorBag;

class SubField extends Field
{
    /**
     * The name of the group, identifying the subfield
     *
     * @var string
     */
    protected $identifier;

    /**
     * The field in which to decorate
     *
     * @var Bozboz\Admin\Fields\Field
     */
    protected $decorate;

    /**
     * The original decorated field name, for retrieval
     *
     * @var string
     */
    protected $originalFieldName;

    /**
     * Rewrite the passed in field's name, to give it context in the group
     *
     * E.g. field_name => group[field_name]
     *
     * @param  string  $identifier
     * @param  Bozboz\Admin\Fields\Field  $field
     */
    public function __construct($identifier, Field $field)
    {
        $this->identifier = $identifier;
        $this->decorate = $field;
        $this->originalFieldName = $field->name;

        $field->name = $this->getFieldNameInBracketSyntax();
    }

    /**
     * Render the decorated field's input output
     *
     * @return string
     */
    public function getInput()
    {
        return $this->decorate->getInput();
    }

    /**
     * Render the decorated field's javascript
     *
     * @return string
     */
    public function getJavascript()
    {
        return $this->decorate->getJavascript();
    }

    /**
     * Render a label for the sub field, using the decorated field's specified
     * label if defined
     *
     * @return string
     */
    public function getLabel()
    {
        return Form::label($this->originalFieldName, $this->decorate->label);
    }

    /**
     * Render output of errors for the sub field
     *
     * @param  Illuminate\Support\ViewErrorBag  $errors
     * @return string
     */
    public function getErrors(ViewErrorBag $errors)
    {
        $key = $this->getFieldNameInDotSyntax();

        if ($errors->has($key)) {
            return '<p><strong>' . $errors->first($key) . '</strong></p>';
        }
    }

    /**
     * Get the name of the subfield in the field group using bracket syntax, for
     * form inputs
     *
     * Example output: group[sub_field]
     *
     * @return string
     */
    protected function getFieldNameInBracketSyntax()
    {
        return $this->identifier . '[' . $this->originalFieldName . ']';
    }

    /**
     * Get the name of the subfield in the field group using dot syntax, for
     * checking error bags
     *
     * Example output: group.sub_field
     *
     * @return string
     */
    protected function getFieldNameInDotSyntax()
    {
        return $this->identifier . '.' . $this->originalFieldName;
    }
}
