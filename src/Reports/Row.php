<?php namespace Bozboz\Admin\Reports;

class Row
{
	private $id;
	private $data;
	private $model;

	public function __construct($id, array $data, $model)
	{
		$this->id = $id;
		$this->data = $data;
		$this->model = $model;
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
