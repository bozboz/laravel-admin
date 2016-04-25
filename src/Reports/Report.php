<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class Report implements BaseInterface, ChecksPermissions
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
		$this->reportActions = collect($actions);
	}

	public function setRowActions($actions)
	{
		$this->rowActions = collect($actions);
	}

	public function getReportActions()
	{
		return $this->reportActions->filter(function($action) {
			return $action->check();
		});
	}

	public function getRowActions()
	{
		return $this->rowActions;
	}

	public function check(callable $assertion)
	{
		return $assertion();
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
			'perPageValue' => Input::get('per-page'),
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
		if ($this->isUsingDeprecatedParams()) {
			$params['newButtonPartial'] = 'admin::partials.new';
			$params['modelName'] = $this->decorator->getHeading(false);
		} else {
			$params = [];
		}

		$params += [
			'sortableClass' => $this->getSortableClass(),
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'identifier' => $this->decorator->getListingIdentifier(),
			'newButtonPartial' => 'admin::partials.create'
		];

		return View::make($this->view, $params);
	}

	protected function getSortableClass()
	{
		$classes = [];

		if ($this->decorator->isDeprecatedSortable()) {
			$classes[] = 'sortable';
		} elseif ($this->decorator->isSortable()) {
			$classes[] = 'js-sortable';
		}

		return $classes ? ' ' . implode(' ', $classes) : '';
	}

	protected function isUsingDeprecatedParams()
	{
		return empty($this->reportActions);
	}
}
