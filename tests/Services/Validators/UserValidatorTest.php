<?php namespace Bozboz\Admin\Test\Services\Validators;

use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Services\Validators\UserValidator;
use Mockery;

class UserValidatorTest extends TestCase
{
	public function testTransformUniquesSuccess()
	{
		$this->assertTrue($this->transformUniques(2));
	}

	public function testTransformUniquesFailure()
	{
		$this->assertFalse($this->transformUniques(3));
	}

	private function transformUniques($userId)
	{
		$userMock = $this->getUserMock($userId);
		$validator = new UserValidator();
		$validator->updateUniques($userMock->getId());
		$success = $validator->passesEdit(array(
			'username' => 'Bower',
			'name' => 'Dan Bower',
			'email' => 'danielb@bozboz.co.uk',
		));

		return $success;
	}

	private function getUserMock($id)
	{
		$mock = Mockery::mock('Model');
		$mock->shouldReceive('getId')->once()->andReturn($id);

		return $mock;
	}
}
