<?php namespace Bozboz\Admin\Test\Services\Validators;

use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Database\Seeds\UserTableSeeder;
use Bozboz\Admin\Services\Validators\UserValidator;
use Mockery;

class UserValidatorTest extends TestCase
{
	/**
	 * Run UserTableSeeder
	 */
	public function setUp()
	{
		parent::setUp();
		$seeder = new UserTableSeeder();
		$seeder->run();
	}

	/**
	 * Unused user ID with new creditionals
	 */
	public function testTransformUniquesCreateSuccess()
	{
		$this->assertTrue($this->transformUniques(1000, [
			'username' => 'Foo',
			'name' => 'Foo Bar',
			'email' => 'foo@bar.com'
		]));
	}

	/**
	 * Unused user ID with existing creditionals
	 */
	public function testTransformUniquesCreateFailure()
	{
		$this->assertFalse($this->transformUniques(1001, [
			'username' => 'admin',
			'email' => 'admin@bozboz.co.uk',
			'name' => 'Boz Admin'
		]));
	}

	/**
	 * Used ID with updated email
	 */
	public function testTransformUniquesUpdateSuccess()
	{
		$this->assertTrue($this->transformUniques(1, [
			'username' => 'admin',
			'email' => 'adminUpdated@bozboz.co.uk',
			'name' => 'Boz Admin'
		]));
	}

	private function transformUniques($userId, $userData)
	{
		$userMock = $this->getUserMock($userId);
		$validator = new UserValidator();
		$validator->updateUniques($userMock->getKey());
		$success = $validator->passesEdit($userData);

		return $success;
	}

	private function getUserMock($id)
	{
		$mock = Mockery::mock('Model');
		$mock->shouldReceive('getKey')->once()->andReturn($id);

		return $mock;
	}
}
