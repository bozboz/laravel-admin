<?php namespace Bozboz\Admin\Tests\Fields;

use Bozboz\Admin\Fields\EmailField;
use TestCase;

class EmailFieldTest extends TestCase
{
	public function testSuccess()
	{
		$field = new EmailField(array('name' => 'some_field', 'value' => 'foo@bar.com'));

		$this->assertContains('some_field', $field->getInput());
		$this->assertContains('foo@bar.com', $field->getInput());
	}

	public function testNoValue()
	{
		$field = new EmailField(array('name' => 'some_field'));

		$this->assertContains('some_field', $field->getInput());
	}
}
