<?php namespace Bozboz\Admin\Tests\Models;

use Bozboz\Admin\Models\User;
use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Database\Seeds\UserTableSeeder;

class BaseTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		$seeder = new UserTableSeeder();
		$seeder->run();
	}
	public function testGetId()
	{
		$userId = 2;
		$user = User::find($userId);

		$this->assertInstanceOf('Bozboz\Admin\Models\Base', $user);
		$this->assertEquals($user->getId(), $userId);
	}
}
