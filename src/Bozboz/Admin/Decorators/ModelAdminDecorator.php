<?php namespace Bozboz\Admin\Decorators;

use Eloquent;

abstract class ModelAdminDecorator
{
	protected $model;

	public function __construct(Eloquent $model)
	{
		$this->model = $model;
	}

	abstract public function getColumns($instance);

	abstract public function getLabel($instance);

	abstract public function getFields();

	public function getModel()
	{
		return $this->model;
	}

	public function getListingModels()
	{
		return $this->model->all();
	}
}
