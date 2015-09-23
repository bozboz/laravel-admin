<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Illuminate\Support\Facades\Config;
use Input;
use View;

class Report
{
	protected $decorator;
	protected $rows;
	protected $view = 'admin::overview';
	protected $renderedColumns = [];

	public function __construct(ModelAdminDecorator $decorator, $view = null)
	{
		$this->decorator = $decorator;
		$this->view = $view ?: $this->view;
		$this->rows = $this->decorator->getListingModels();
	}

	/**
	 * @deprecated
	 *
	 * @param  string  $view
	 * @return void
	 */
	public function overrideView($view)
	{
		$this->view = $view;
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

		return new Row($id, $instance, $this->getColumnsFromInstance($instance));
	}

	protected function getColumnsFromInstance($instance)
	{
		return $this->decorator->getColumns($instance);
	}

	public function getHeader()
	{
		$filters = $this->decorator->getListingFilters();
		$perPageOptions = $this->decorator->getItemsPerPageOptions();

		return View::make('admin::partials.listing-filters')->with(compact('perPageOptions'))->withFilters($filters);
	}

	public function getFooter()
	{
		if (method_exists($this->rows, 'links')) {
			return $this->rows->appends(Input::except('page'))->links();
		}
	}

	public function render(array $params)
	{
		$identifier = $this->decorator->getListingIdentifier();

		$deprecatedParams = [
			'fullModelName' => $identifier
		];

		if (array_key_exists('controller', $params)) {
			$deprecatedParams += app($params['controller'])->getReportParams();
		}

		$params += $deprecatedParams + [
			'sortableClass' => $this->decorator->isSortable() ? ' sortable' : '',
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'modelName' => $this->decorator->getHeading(false),
			'identifier' => $identifier,
			'canCreate' => true,
			'canEdit' => true,
			'canDelete' => true
		];

		return View::make($this->view, $params);
	}
}
