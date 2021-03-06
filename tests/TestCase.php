<?php namespace Bozboz\Admin\Tests;

use Mockery;
use Artisan, Mail;

class TestCase extends \TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->prepareForTests();
	}

	private function prepareForTests()
	{
		Mail::pretend(true);
	}

	public function tearDown()
	{
		Mockery::close();
	}
}
