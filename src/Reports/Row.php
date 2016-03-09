<?php namespace Bozboz\Admin\Reports;

class Row implements ChecksPermissions
{
	private $id;
	private $data;
	private $model;

	public function __construct($id, $modelOrData, array $data = null)
	{
		$this->id = $id;
		if (is_array($modelOrData)) {
			$this->data = $modelOrData;
		} else {
			$this->model = $modelOrData;
			$this->data = $data;
		}
	}

	public function filterRowActions($actions)
	{
		return $actions->filter(function($action) {
			return $action->check($this->model);
		})->each(function($action) {
			$action->setInstance($this->model);
		});
	}

	public function getId()
	{
		return $this->id;
	}

	public function getColumns()
	{
		return $this->data;
	}

	public function getColumn($name)
	{
		return $this->data[$name];
	}

	/**
	 * Check if underlying entity can do something, based on result of passed-in
	 * callable.
	 *
	 * @param  callable  $assertion
	 * @return boolean
	 */
	public function check(callable $assertion)
	{
		return $assertion($this->model);
	}
}
