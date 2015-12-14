<?php namespace Bozboz\Admin\Controllers;

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

	public function getListingReport()
	{
		return new NestedReport($this->decorator);
	}

	public function viewPermissions($stack)
	{
		$stack->add('view_pages');
	}

	public function createPermissions($stack, $instance)
	{
		$stack->add('create_page', $instance);
	}

	public function editPermissions($stack, $instance)
	{
		$stack->add('edit_page', $instance);
	}

	public function deletePermissions($stack, $instance)
	{
		$stack->add('delete_page', $instance);
	}
}
