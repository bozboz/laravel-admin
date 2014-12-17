<?php namespace Bozboz\Admin\Test\Services\Validators;

use Mockery;
use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Tests\Stubs\Services\Validators\ValidatorStub;

class ValidatorTest extends TestCase
{
	public function test_transform_uniques_with_just_unique_constraint()
	{
		$rules = ['email' => 'unique:users'];
		$expectedOutput = ['email' => 'unique:users,email,1'];

		$this->transformUniques($rules, $expectedOutput);
		$this->assertTrue(true); //Will error out before this if test failed
	}

	public function test_transform_uniques_with_multiple_constraints()
	{
		$rules = ['email' => 'required|unique:users|max:255'];
		$expectedOutput = ['email' => 'required|unique:users,email,1|max:255'];

		$this->transformUniques($rules, $expectedOutput);
		$this->assertTrue(true); //Will error out before this if test failed
	}

	public function test_transform_uniques_with_unique_first()
	{
		$rules = ['email' => 'unique:users|max:255|required'];
		$expectedOutput = ['email' => 'unique:users,email,1|max:255|required'];

		$this->transformUniques($rules, $expectedOutput);
		$this->assertTrue(true); //Will error out before this if test failed
	}

	public function test_transform_uniques_with_unique_last()
	{
		$rules = ['email' => 'required|max:255|unique:users'];
		$expectedOutput = ['email' => 'required|max:255|unique:users,email,1'];

		$this->transformUniques($rules, $expectedOutput);
		$this->assertTrue(true); //Will error out before this if test failed
	}

	private function transformUniques($rules, $expectedOutput)
	{
		$userData = ['email' => 'admin@bozboz.co.uk'];
		$userMock = $this->getUserMock(1);
		$validator = new ValidatorStub($rules);
		$validator->updateUniques($userMock->getKey());

		$validatorMock = Mockery::mock('Illuminate\Validation\Factory');
		$validatorMock->shouldReceive('make')->with($userData, $expectedOutput)->andReturn($validatorMock);
		$validatorMock->shouldReceive('passes')->andReturn(true);
		\App::instance('validator', $validatorMock);

		$validator->passesEdit($userData);
	}

	private function getUserMock($id)
	{
		$mock = Mockery::mock('Model');
		$mock->shouldReceive('getKey')->once()->andReturn($id);

		return $mock;
	}
}
