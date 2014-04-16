<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Decorators\PageAdminDecorator;

class PageAdminController extends ModelAdminController
{
	protected $listingView = 'admin::overview-nested';

	public function __construct(PageAdminDecorator $page)
	{
		parent::__construct($page);
	}
}
