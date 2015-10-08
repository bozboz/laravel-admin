<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Decorators\PageAdminDecorator;
use Bozboz\Admin\Services\Sorter;
use Bozboz\Admin\Reports\NestedReport;
use Input;

class PageAdminController extends ModelAdminController
{
	private $sorter;

	public function __construct(PageAdminDecorator $page, Sorter $sorter)
	{
		$this->sorter = $sorter;
		parent::__construct($page);
	}

	protected function getListingReport()
	{
		return new NestedReport($this->decorator);
	}
}
