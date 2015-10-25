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

		foreach($this->rows as $inst) {
			$inst->parent_id = $inst->parent_id ?: 0;
			$this->tree[$inst->parent_id][] = $this->getRowFromInstance($inst);
		}

		return count($this->tree)? $this->tree[0] : array();
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
