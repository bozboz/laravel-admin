<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Models\Page;
use View, Redirect;

class PageController extends \Controller
{
	public function show($slug)
	{
		$page = Page::where('slug', $slug)->firstOrFail();

		if ($page->redirect_to_id) {
			return $this->redirectTo($page->redirectToPage);
		} else {
			return $this->serveView($page);
		}
	}

	protected function redirectTo(Page $page)
	{
		return Redirect::to($page->slug);
	}

	protected function serveView(Page $page)
	{
		$view = $page->template? 'pages.' . $page->template : 'admin::pages.page';
		return View::make($view, compact('page'));
	}
}
