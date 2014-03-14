<?php namespace Bozboz\Admin\Tests\FieldMapping;

use Bozboz\Admin\FieldMapping\Field;
use TestCase;

class FieldTest extends TestCase
{
	public function testGetLabel()
	{
		$label = 'Some label';
		$name = 'some_attribute';
		$field = new Field(array('name' => $name, 'label' => $label));
		$this->assertContains($label, $field->getLabel());
		$this->assertContains($name, $field->getLabel());
	}

	public function testGetSelectInput()
	{
		$field = new Field(array('name' => 'some_field', 'type' => 'select', 'params' => array('one', 'two', 'three')));

		$this->assertContains('select', $field->getInput());
		$this->assertContains('one', $field->getInput());
		$this->assertContains('two', $field->getInput());
		$this->assertContains('three', $field->getInput());
	}

	public function testGetTextInput()
	{
		$field = new Field(array('name' => 'some_field', 'type' => 'text'));

		$this->assertContains('some_field', $field->getInput());
		$this->assertContains('text', $field->getInput());
	}
}