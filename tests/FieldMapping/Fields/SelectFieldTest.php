<?php namespace Bozboz\Admin\Tests\FieldMapping\Fields;

use Bozboz\Admin\FieldMapping\Fields\SelectField;
use TestCase;

class SelectFieldTest extends TestCase
{
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFail()
	{
		$field = new SelectField(array('name' => 'some_field'));
		$field->getInput();
	}

	public function testSuccess()
	{
		$options = array('foo', 'bar', 'baz');
		$field = new SelectField(array('name' => 'some_field', 'options' => $options));

		$fieldHtml = $field->getInput();
		foreach ($options as $option) {
			$this->assertContains($option, $fieldHtml);
		}

		$this->assertContains('select', $field->getInput());
	}
}
