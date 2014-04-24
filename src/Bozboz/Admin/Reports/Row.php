<?php namespace Bozboz\Admin\Reports;

class Row
{
	private $id;
	private $data;
	private $model;

	public function __construct($id, $model, array $data)
	{
		$this->id = $id;
		$this->model = $model;
		$this->data = $data;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getColumns()
	{
		return $this->data;
	}

	public function getModel()
	{
		return $this->model;
	}
}
