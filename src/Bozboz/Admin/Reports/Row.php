<?php namespace Bozboz\Admin\Reports;

class Row
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

	public function getId()
	{
		return $this->id;
	}

	public function getColumns()
	{
		return $this->data;
	}

	/**
	 * DEPRECATED
	 *
	 * @return mixed
	 */
	public function getModel()
	{
		return $this->model;
	}
}
