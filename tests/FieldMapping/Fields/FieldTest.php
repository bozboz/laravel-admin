<?php namespace Bozboz\Admin\Tests\FieldMapping\Fields;

use Bozboz\Admin\FieldMapping\Fields\TextField;
use Bozboz\Admin\FieldMapping\Fields\SelectField;
use Bozboz\Admin\FieldMapping\Fields\TextareaField;
use TestCase;

class FieldTest extends TestCase
{
	public function testGetLabel()
	{
		$label = 'Some label';
		$name = 'some_attribute';
		$field = new TextField(array('name' => $name, 'label' => $label));
		$this->assertContains($label, $field->getLabel());
		$this->assertContains($name, $field->getLabel());
	}

	public function testGetTextInput()
	{
		$field = new TextField(array('name' => 'some_field'));

		$this->assertContains('some_field', $field->getInput());
		$this->assertContains('text', $field->getInput());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function getSelectInputFail()
	{
		$field = new SelectField(array('name' => 'some_field'));
	}

	public function testGetSelectInputSuccess()
	{
		$field = new SelectField(array('name' => 'some_field', 'options' => array('foo', 'bar', 'baz')));

		$this->assertContains('select', $field->getInput());
		$this->assertContains('foo', $field->getInput());
		$this->assertContains('bar', $field->getInput());
		$this->assertContains('baz', $field->getInput());
	}

	public function testGetTextarea()
	{
		$field = new TextareaField(array('name' => 'some_field'));

		$this->assertContains('some_field', $field->getInput());
		$this->assertContains('<textarea', $field->getInput());
	}
}
