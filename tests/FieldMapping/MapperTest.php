<?php namespace Bozboz\Admin\Tests\FieldMapping;

use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\FieldMapping\Mapper;
use Mockery;

class MapperTest extends TestCase
{
	protected $mapper;

	public function setUp()
	{
		parent::setUp();
		$this->mapper = new Mapper;
	}

	public function testGetFields()
	{
		$fields = $this->mapper->getFields(array(
			'name' => array(
				'type' => 'text',
				'label' => 'Your Name'
			)
		));
		foreach($fields as $field) {
			$this->assertInstanceOf('Bozboz\Admin\FieldMapping\Field', $field);
			$fieldAttributes = $field->toArray();
			$this->assertArrayHasKey('name', $fieldAttributes);
			$this->assertArrayHasKey('label', $fieldAttributes);
			$this->assertArrayHasKey('type', $fieldAttributes);
			$this->assertEquals('Your Name', $field->get('label'));
			$this->assertEquals('name', $field->get('name'));
			$this->assertEquals('text', $field->get('type'));
		}
	}
}
