<?php namespace Bozboz\Admin\Tests\Fields;

use Bozboz\Admin\Fields\PasswordField;
use TestCase;

class PasswordFieldTest extends TestCase
{
	public function testSuccess()
	{
		$field = new PasswordField(array('name' => 'some_field'));

		$this->assertContains('name="some_field"', $field->getInput());
	}
}
