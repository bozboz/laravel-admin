<?php namespace Bozboz\Admin\Reports;

class Row
{
	private $id;
	private $data;

	public function __construct($id, array $data = null)
	{
		$this->id = $id;
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

	public function getColumn($name)
	{
		return $this->data[$name];
	}
}
