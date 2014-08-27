<?php namespace Bozboz\Admin\Decorators;

use Event;
use Bozboz\Admin\Models\Base;

abstract class ModelAdminDecorator
{
	protected $model;

	public function __construct(Base $model)
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

	public function buildFields()
	{
		$fieldsObj = new \Illuminate\Support\Fluent($this->getFields());
		Event::fire('admin.fields.built', array($fieldsObj, $this->getModel()));

		return $fieldsObj->toArray();
	}
}
