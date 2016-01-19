<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class Report implements BaseInterface
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
		$perPageValue = Input::get('per-page');

		return View::make('admin::partials.listing-filters')->with(compact('perPageOptions', 'perPageValue'))->withFilters($filters);
	}

	public function getFooter()
	{
		if (method_exists($this->rows, 'render')) {
			return $this->rows->appends(Input::except('page'))->render();
		}
	}

	public function render(array $params = [])
	{
		$identifier = $this->decorator->getListingIdentifier();

		$params += [
			'sortableClass' => $this->getSortableClass(),
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'modelName' => $this->decorator->getHeading(false),
			'identifier' => $identifier,
			'newButtonPartial' => 'admin::partials.new',
		];

		return View::make($this->view, $params);
	}

	protected function getSortableClass()
	{
		$classes = [];

		if ($this->decorator->isSortable()) {
			$classes[] = 'sortable';
		}

		if ($this->decorator->isDeprecatedSortable()) {
			$classes[] = 'deprecated-sortable';
		}

		return $classes ? ' ' . implode(' ', $classes) : '';
	}
}
