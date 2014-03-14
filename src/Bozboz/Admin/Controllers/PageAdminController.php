<?php namespace Bozboz\Admin\Controllers;

use Bozboz\Admin\Decorators\PageAdminDecorator;

class PageAdminController extends ModelAdminController
{
	public function __construct(PageAdminDecorator $page)
	{
		parent::__construct($page);
	}
}
