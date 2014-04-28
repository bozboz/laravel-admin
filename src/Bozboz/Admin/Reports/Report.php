<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use View;

class Report
{
	protected $decorator;
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

	public function getRows()
	{
		$rows = array();
		$instances = $this->decorator->getListingModels();
		foreach($instances as $row) {
			$rows[] = new Row($row->id, $row, $this->decorator->getColumns($row));
		}
		return $rows;
	}

	public function render(array $params)
	{
		$params['report'] = $this;
		$params['modelName'] = class_basename(get_class($this->decorator->getModel()));
		return View::make($this->view, $params);
	}
}
