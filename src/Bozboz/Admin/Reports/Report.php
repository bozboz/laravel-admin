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
		$this->rows = $this->decorator->getListingModels();
		return count($this->rows) > 0;
	}

	public function getRows()
	{
		$rows = array();
		$this->rows = $this->rows ?: $this->decorator->getListingModels();

		foreach($this->rows as $row) {
			$rows[] = new Row($row->id, $row, $this->decorator->getColumns($row));
		}

		return $rows;
	}

	public function getHeader()
	{
		$values = [];
		$filters = $this->decorator->getListingFilters();

		foreach($filters as $filter) {
			$values[$filter->getName()] = $filter->getValue();
		}

		return View::make('admin::partials.listing-filters')->withInput($values)->withFilters($filters);
	}

	public function getFooter()
	{
		return method_exists($this->rows, 'links') ? $this->rows->appends(\Input::except('page'))->links() : null;
	}

	public function render(array $params)
	{
		$params['sortableClass'] = $this->decorator->getModel() instanceof Sortable ? ' sortable' : '';
		$params['report'] = $this;
		$params['fullModelName'] = get_class($this->decorator->getModel());
		$params['modelName'] = $this->decorator->getHeading(true);
		return View::make($this->view, $params);
	}
}
