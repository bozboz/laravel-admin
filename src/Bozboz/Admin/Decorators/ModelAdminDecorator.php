<?php namespace Bozboz\Admin\Decorators;

use Event, Str, Config;
use Bozboz\Admin\Models\Base;
use Bozboz\Admin\Models\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

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

	/**
	 * Retrieve $this->model
	 *
	 * @return Bozoboz\Admin\Models\Base
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Retrieve a heading representing $this->model
	 *
	 * @param  boolean  $plural
	 * @return string
	 */
	public function getHeading($plural = false)
	{
		$name = class_basename(get_class($this->model));
		return $plural ? Str::plural($name) : $name;
	}

	/**
	 * Apply each defined listing filter to the passed $builder
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function filterListingQuery(Builder $query)
	{
		foreach($this->getListingFilters() as $listingFilter) {
			$listingFilter->filter($query);
		}
	}

	protected function modifyListingQuery(Builder $query)
	{
		if ($this->model instanceof Sortable) {
			$query->orderBy($this->model->sortBy());
		} elseif ($this->model->usesTimestamps()) {
			$query->orderBy($this->model->getTable() . '.created_at', 'DESC');
		}
	}

	/**
	 * Retrieve a collection of instances of $this->model to display
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function getListingModels()
	{
		$query = $this->model->newQuery();

		$this->filterListingQuery($query);

		$this->modifyListingQuery($query);

		return $query->paginate(Config::get('admin::listing_items_per_page'));
	}

	/**
	 * Get an array of listing filters
	 *
	 * @return array
	 */
	public function getListingFilters()
	{
		return [];
	}

	/**
	 * Build an array of fields
	 *
	 * @param  Bozboz/Admin/Models/Base  $instance
	 * @return array
	 */
	public function buildFields($instance)
	{
		$fieldsObj = new Fluent($this->getFields($instance));

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
