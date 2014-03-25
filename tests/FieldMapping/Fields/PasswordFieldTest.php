<?php namespace Bozboz\Admin\Tests\FieldMapping\Fields;

use Bozboz\Admin\FieldMapping\Fields\PasswordField;
use TestCase;

class PasswordFieldTest extends TestCase
{
	public function testSuccess()
	{
		$field = new PasswordField(array('name' => 'some_field'));

		$this->assertContains('name="some_field"', $field->getInput());
		$this->assertContains('class="foo_class"', $field->getInput(array('class' => 'foo_class')));
	}
}
