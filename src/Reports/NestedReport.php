<?php namespace Bozboz\Admin\Reports;

class NestedReport extends Report
{
	protected $view = 'admin::overview-nested';
	private $tree;

	public function getRows()
	{
		$tree = array();

		foreach($this->rows as $inst) {
			$index = $inst->parent_id ?: 0;
			$this->tree[$index][] = $this->getRowFromInstance($inst);
		}

		return count($this->tree)? reset($this->tree) : array();
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
