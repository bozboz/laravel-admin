<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Models\Page;
use View;

class PageController extends \Controller
{
	public function show($slug)
	{
		$page = Page::where('slug', $slug)->firstOrFail();
		$view = $page->template? 'pages.' . $page->template : 'admin::pages.page';

		return View::make($view, compact('page'));
	}
}
