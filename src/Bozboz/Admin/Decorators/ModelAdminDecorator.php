<?php namespace Bozboz\Admin\Decorators;

use Event, Str;
use Bozboz\Admin\Models\Base;
use Illuminate\Database\Eloquent\Builder;

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

	public function getHeading($plural = false)
	{
		$name = class_basename(get_class($this->model));
		return $plural ? Str::plural($name) : $name;
	}

	protected function filterListing(Builder $builder)
	{
		foreach($this->getListingFilters() as $listingFilter) {
			$listingFilter->filter($builder);
		}
	}

	public function getListingModels()
	{
		return $this->model->all();
	}

	public function getListingFilters()
	{
		return [];
	}

	public function buildFields($instance = null)
	{
		$instance = $instance ?: $this->getModel();
		$fieldsObj = new \Illuminate\Support\Fluent($this->getFields($instance));
		Event::fire('admin.fields.built', array($fieldsObj, $instance));

		return $fieldsObj->toArray();
	}

	/**
	 * @param  array  $attributes
	 * @return Bozboz\Admin\Models\Base
	 */
	public function newModelInstance($attributes = array())
	{
		return $this->model->newInstance();
	}

	/**
	 * @param  int  $id
	 * @return Bozboz\Admin\Models\Base
	 */
	public function findInstance($id)
	{
		return $this->model->find($id);
	}

	/**
	 * Get the names of the many-to-many relationships defined on the model
	 * that need to be processed.
	 *
	 * @return array
	 */
	public function getSyncRelations()
	{
		return [];
	}

	/**
	 * Set the related IDs as an attribute on the $instance.
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return void
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
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @param  array  $formInput
	 * @return void
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

	/**
	 * Sanitise input on $this->model
	 *
	 * @param  array  $input
	 * @return array
	 */
	public function sanitiseInput($input)
	{
		return $this->model->sanitiseInput($input);
	}
}
