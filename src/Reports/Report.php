<?php namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Exceptions\Deprecated;
use Bozboz\Admin\Reports\Actions\CreateAction;
use Bozboz\Admin\Reports\Actions\DestroyAction;
use Bozboz\Admin\Reports\Actions\EditAction;
use Bozboz\Admin\Reports\Filters\ListingFilter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class Report implements BaseInterface, ChecksPermissions
{
	protected $decorator;
	protected $rows;
	protected $reportActions;
	protected $rowActions;
	protected $view = 'admin::overview';
	protected $renderedColumns = [];

	public function __construct(ModelAdminDecorator $decorator, $view = null)
	{
		$this->decorator = $decorator;
		$this->view = $view ?: $this->view;
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
		if ( ! $this->reportActions) {
			$this->reportActions = collect();
		}

		return $this->reportActions->filter(function($action) {
			return $action->check();
		});
	}

	public function getRowActions()
	{
		if ( ! $this->rowActions) {
			$this->rowActions = collect();
		}

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
		return View::make('admin::partials.listing-filters')->withFilters(
			$this->decorator->getListingFilters()
		);
	}

	public function getFooter()
	{

	}

	protected function queryRows()
	{
		try {
			return $this->decorator->getListingModels();
		} catch (Deprecated $e) {
			return $this->decorator->getListingModelsNoLimit();
		}
	}

	public function render(array $params = [])
	{
		$this->rows = $this->queryRows();

		if ($this->isUsingDeprecatedParams($params)) {
			$params['modelName'] = $this->decorator->getHeading(false);
			$this->setRowActions([
				new EditAction(
					$params['editAction'],
					$params['canEdit']
				),
				new DestroyAction(
					$params['destroyAction'],
					$params['canDelete']
				)
			]);
			$this->setReportActions([
				new CreateAction(
					$params['createAction'],
					$params['canCreate'],
					['label' => 'New ' . $this->decorator->getHeading()]
				)
			]);
		}

		$params += [
			'sortableClass' => $this->getSortableClass(),
			'report' => $this,
			'heading' => $this->decorator->getHeading(true),
			'identifier' => $this->decorator->getListingIdentifier(),
			'newButtonPartial' => 'admin::partials.create',
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

	protected function isUsingDeprecatedParams($params)
	{
		return array_key_exists('createAction', $params);
	}

	public function injectValues($values)
	{
		ListingFilter::injectValues($values);
	}
}
