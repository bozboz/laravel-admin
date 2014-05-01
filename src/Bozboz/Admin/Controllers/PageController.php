<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Meta\Provider as Meta;
use Bozboz\Admin\Models\Page;
use View, Redirect;

class PageController extends \Controller
{
	public function show($slug)
	{
		$page = Page::where('slug', $slug)->firstOrFail();

		if ($page->redirect_to_id) {
			return $this->redirectTo(Page::findOrFail($page->redirect_to_id));
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
		$meta = Meta::forPage($page);
		return View::make($view, compact('page', 'meta'));
	}
}
