<?php namespace Bozboz\Admin\Tests\Traits;

use TestCase;
use Bozboz\Admin\Models\Page;
use Bozboz\Admin\Database\Seeds\PagesTableSeeder;

class DynamicSlugTraitTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		Page::truncate();
		$seeder = new PagesTableSeeder();
		$seeder->run();
	}

	public function test_generate_slug_with_duplicate()
	{
		$page = new Page();
		$page->title = 'Page #1';
		$page->save();

		$expectedOutput = 'page-4'; //page-1, page-2 and page-3 are already slugs
		$actualOutput = $page->slug;

		$this->assertEquals($expectedOutput, $actualOutput);
	}
}
