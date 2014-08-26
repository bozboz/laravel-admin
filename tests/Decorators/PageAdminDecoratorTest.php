<?php namespace Bozboz\Admin\Test\Decorators;

use Mockery;
use Bozboz\Admin\Models\Page;
use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Decorators\PageAdminDecorator;

class PageAdminDecoratorTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		$seeder = new \Bozboz\Admin\Database\Seeds\PagesTableSeeder();
		$seeder->run();
	}

	public function testLabel()
	{
		$page = Page::find(1);
		$decorator = new PageAdminDecorator($page);

		$this->assertEquals($page->title, $decorator->getLabel($page));
	}

	public function testGetRedirectOptions()
	{
		$expectedOutput = ['' => 'No redirect'];
		foreach (Page::all() as $page) {
			$expectedOutput[$page->id] = $page->slug;
		}

		$method = new \ReflectionMethod('Bozboz\Admin\Decorators\PageAdminDecorator', 'getRedirectOptions');
		$method->setAccessible(true);

		$this->assertEquals($expectedOutput, $method->invoke(new \Bozboz\Admin\Decorators\PageAdminDecorator(new Page())));
	}
}
