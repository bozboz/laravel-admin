<?php namespace Bozboz\Admin\Tests\FieldMapping\Fields;

use Bozboz\Admin\FieldMapping\Fields\TextareaField;
use TestCase;

class TextareaFieldTest extends TestCase
{
	public function testSuccess()
	{
		$field = new TextareaField(array('name' => 'some_field'));

		$this->assertContains('some_field', $field->getInput());
		$this->assertContains('<textarea', $field->getInput());
	}
}
