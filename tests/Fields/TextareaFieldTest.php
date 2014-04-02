<?php namespace Bozboz\Admin\Tests\Fields;

use Bozboz\Admin\Fields\TextareaField;
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
