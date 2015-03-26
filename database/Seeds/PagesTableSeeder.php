<?php namespace Bozboz\Admin\Database\Seeds;

use Bozboz\Admin\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
	public function run()
	{
		Page::truncate();
		$sorting = 0;
		$date = date('Y-m-d H:i:s');
		$pagesData = array(
			array(
				'title' => 'Page #1',
				'slug' => 'page-1',
				'description' => 'Page #1',
				'created_at' => $date,
				'updated_at' => $date,
				'redirect_to_id' => 0,
				'meta_title' => 'Page #1',
				'meta_description' => 'Page #1',
				'sorting' => $sorting++,
				'children' => array(
					array(
						'title' => 'Child Page #1',
						'slug' => 'child-page-1',
						'description' => 'Child Page #1',
						'created_at' => $date,
						'updated_at' => $date,
						'redirect_to_id' => 0,
						'meta_title' => 'Child Page #1',
						'meta_description' => 'Child Page #1',
						'sorting' => $sorting++,
					),
				)
			),
			array(
				'title' => 'Page #2',
				'slug' => 'page-2',
				'description' => 'Page #2',
				'created_at' => $date,
				'updated_at' => $date,
				'redirect_to_id' => 0,
				'meta_title' => 'Page #2',
				'meta_description' => 'Page #2',
				'sorting' => $sorting++,
			),
			array(
				'title' => 'Page #3',
				'slug' => 'page-3',
				'description' => 'Page #3',
				'created_at' => $date,
				'updated_at' => $date,
				'redirect_to_id' => 0,
				'meta_title' => 'Page #3',
				'meta_description' => 'Page #3',
				'sorting' => $sorting++,
			),
		);

		foreach ($pagesData as $pageData) {
			$children = null;
			if (isset($pageData['children'])) {
				$children = $pageData['children'];
				unset($pageData['children']);
			}
			$page = new Page($pageData);
			$page->save();
			if (isset($children)) {
				foreach ($children as $child) {
					$childPage = new Page($child);
					$childPage->parent_id = $page->id;
					$childPage->save();
				}
			}
		}
	}
}
