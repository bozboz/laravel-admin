<?php namespace Bozboz\Admin\Tests;

use Artisan;

class TestCase extends \TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->prepareForTests();
	}

	private function prepareForTests()
	{
		$option = strpos(__DIR__, 'workbench') === false ? '--package' : '--bench';

		Artisan::call('migrate:reset');
		Artisan::call('migrate', array($option => 'bozboz/admin'));
		Artisan::call('db:seed');
	}
}
