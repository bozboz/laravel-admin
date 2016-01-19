<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class Report implements BaseInterface
{
	protected $decorator;
	protected $rows;
	protected $view = 'admin::overview';
	protected $reportActions = [];
	protected $rowActions = [];
	protected $renderedColumns = [];

	public function __construct(ModelAdminDecorator $decorator, $view = null)
	{
		$this->decorator = $decorator;
		$this->view = $view ?: $this->view;
		$this->rows = $this->decorator->getListingModels();
	}

	public function setReportActions($actions)
	{
		$this->reportActions = $actions;
	}

	public function setRowActions($actions)
	{
		$this->rowActions = $actions;
	}

	public function getActions()
	{
		return $this->reportActions;
	}

	public function getHeadings()
	{
		$firstRow = $this->getRowFromInstance($this->rows->first());

		$this->renderedColumns[$firstRow->getId()] = $firstRow;

		return array_keys($firstRow->getColumns());
	}

	public function hasRows()
	{
		return count($this->rows) > 0;
	}

	public function getRows()
	{
		$rows = [];

		foreach($this->rows as $row) {
			$rows[] = $this->getRowFromInstance($row);
		}

		return $rows;
	}

	protected function getRowFromInstance($instance)
	{
		$id = $instance->id;

		if (array_key_exists($id, $this->renderedColumns)) {
			return $this->renderedColumns[$id];
		}

		return new Row($id, $instance, $this->getColumnsFromInstance($instance), $this->rowActions);
	}

	protected function getColumnsFromInstance($instance)
	{
		return $this->decorator->getColumns($instance);
	}

	public function getHeader()
	{
		return View::make('admin::partials.listing-filters')->with([
			'perPageOptions' => $this->decorator->getItemsPerPageOptions(),
			'filters' => $this->decorator->getListingFilters(),
		]);
	}

	public function getFooter()
	{
		if (method_exists($this->rows, 'render')) {
			return $this->rows->appends(Input::except('page'))->render();
		}
	}

	public function render(array $params = [])
	{
		$params += [
			'sortableClass' => $this->decorator->isSortable() ? ' sortable' : '',
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'identifier' => $this->decorator->getListingIdentifier(),
		];

		return View::make($this->view, $params);
	}
}
