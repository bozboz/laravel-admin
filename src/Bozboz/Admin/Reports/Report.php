<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Models\Sortable;
use View;

class Report
{
	protected $decorator;
	protected $rows;
	protected $view = 'admin::overview';

	public function __construct(ModelAdminDecorator $decorator)
	{
		$this->decorator = $decorator;
		$this->rows = $this->decorator->getListingModels();
	}

	public function overrideView($view)
	{
		$this->view = $view;
	}

	public function getHeadings()
	{
		return array_keys($this->decorator->getColumns($this->decorator->getModel()));
	}

	public function hasRows()
	{
		return count($this->rows) > 0;
	}

	public function getRows()
	{
		$rows = array();

		foreach($this->rows as $row) {
			$rows[] = new Row($row->id, $row, $this->decorator->getColumns($row));
		}

		return $rows;
	}

	public function getHeader()
	{
		$filters = $this->decorator->getListingFilters();

		return View::make('admin::partials.listing-filters')->withFilters($filters);
	}

	public function getFooter()
	{
		return method_exists($this->rows, 'links') ? $this->rows->appends(\Input::except('page'))->links() : null;
	}

	public function render(array $params)
	{
		$params = array_merge([
			'sortableClass' => $this->decorator->getModel() instanceof Sortable ? ' sortable' : '',
			'report' => $this,
			'fullModelName' => get_class($this->decorator->getModel()),
			'modelName' => $this->decorator->getHeading(true),
			'canCreate' => true
		], $params);

		return View::make($this->view, $params);
	}
}
