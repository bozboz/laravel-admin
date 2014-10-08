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

	abstract public function getFields($instance);

	public function getModel()
	{
		return $this->model;
	}

	public function getListingModels()
	{
		return $this->model->all();
	}

	public function buildFields($instance = null)
	{
		$instance = $instance ?: $this->getModel();
		$fieldsObj = new \Illuminate\Support\Fluent($this->getFields($instance));
		Event::fire('admin.fields.built', array($fieldsObj, $instance));

		return $fieldsObj->toArray();
	}

	/**
	 * Get the names of the many-to-many relationships defined on the model that need to be processed.
	 *
	 * @return array Names of many-to-many relationships that need to be synced
	 */
	public function getSyncRelations()
	{
		return [];
	}

	/**
	 * Sets the related IDs as an attribute on the $instance.
	 */
	public function injectSyncRelations(Base $instance)
	{
		foreach ($this->getSyncRelations() as $relationName) {
			$relation = $instance->$relationName();
			$instance->setAttribute($relationName . '_relationship', $relation->getRelatedIds());
		}
	}

	/**
	 * Update the many-to-many relationship mappings after a form submission.
	 */
	public function updateSyncRelations(Base $instance, $formInput)
	{
		foreach ($this->getSyncRelations() as $relationship) {
			if (isset($formInput[$relationship . '_relationship'])) {
				$data = $formInput[$relationship . '_relationship'];
				$instance->$relationship()->sync(is_array($data) ? $data : array());
			}
		}
	}
}
