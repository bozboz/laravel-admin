<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Meta\Provider as Meta;
use Bozboz\Admin\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use View, Redirect, Controller, Request;

class PageController extends Controller
{
	/**
	 * Lookup page by slug; and either render corresponding view, redirect to
	 * another page, or throw a NotFoundHttpException.
	 *
	 * @param  string  $slug
	 * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 * @return Symfony\Component\HttpFoundation\Response
	 */
	public function show($slug)
	{
		try {
			$page = Page::where('slug', $slug)->firstOrFail();

			if ($page->redirect_to_id) {
				return $this->redirectTo(Page::findOrFail($page->redirect_to_id));
			} else {
				return $this->serveView($page);
			}
		} catch (ModelNotFoundException $e) {
			throw new NotFoundHttpException('Page with slug "' . Request::path() . '" was not found.');
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
