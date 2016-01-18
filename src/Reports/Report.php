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

		return View::make('admin::partials.listing-filters')->with(compact('perPageOptions'))->withFilters($filters);
	}

	public function getFooter()
	{
		if (method_exists($this->rows, 'render')) {
			return $this->rows->appends(Input::except('page'))->render();
		}
	}

	public function getRowActions($params)
	{
		return [
			new Actions\LinkAction([
				'permission' => $params['canEdit'],
				'action' => $params['editAction'],
				'label' => 'Edit',
				'icon' => 'fa fa-pencil',
				'class' => 'btn-info'
			]),
			new Actions\FormAction([
				'permission' => $params['canDelete'],
				'action' => $params['destroyAction'],
				'method' => 'DELETE',
				'label' => 'Delete',
				'icon' => 'fa fa-minus-square',
				'class' => 'btn-danger',
				'warn' => 'Are you sure you want to delete?'
			])
		];
	}
	public function render(array $params = [])
	{
		$identifier = $this->decorator->getListingIdentifier();

		$rowActions = $this->getRowActions($params);

		$params += [
			'sortableClass' => $this->decorator->isSortable() ? ' sortable' : '',
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'modelName' => $this->decorator->getHeading(false),
			'identifier' => $identifier,
			'newButtonPartial' => 'admin::partials.new',
			'rowActions' => $rowActions
		];

		return View::make($this->view, $params);
	}
}
