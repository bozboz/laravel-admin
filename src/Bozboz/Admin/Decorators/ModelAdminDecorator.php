<?php namespace Bozboz\Admin\Decorators;

use Eloquent;
use ArrayAccess;

abstract class ModelAdminDecorator
{
	protected static $fields = array();
	protected $model;

	public function __construct(Eloquent $model)
	{
		$this->model = $model;
	}

	abstract public function getColumns($instance);

	abstract public function getLabel($instance);

	public function getModel()
	{
		return $this->model;
	}

	public function getListingModels()
	{
		return $this->model->all();
	}

	public function getFields()
	{
		return static::$fields;
	}
}
