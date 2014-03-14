<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Models\Page;
use View;

class PageController extends \Controller
{
	public function show($slug)
	{
		$page = Page::where('slug', $slug)->firstOrFail();
		return View::make('admin::pages.page', compact('page'));
	}
}
