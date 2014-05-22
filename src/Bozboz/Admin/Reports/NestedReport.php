<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Models\Sortable;

class NestedReport extends Report
{
	protected $view = 'admin::overview-nested';
	private $tree;

	public function getRows()
	{
		$tree = array();

		$instances = $this->rows? $this->rows : $this->decorator->getListingModels();
		foreach($instances as $page) {
			$this->tree[$page->parent_id][] = new Row($page->id, $page, $this->decorator->getColumns($page));
		}

		return count($tree)? $this->tree[0] : array();
	}

	public function isRowNested(Row $row)
	{
		return isset($this->tree[$row->getId()]);
	}

	public function getChildrenFor(Row $row)
	{
		if ($this->isRowNested($row)) {
			return $this->tree[$row->getId()];
		}
	}
}
