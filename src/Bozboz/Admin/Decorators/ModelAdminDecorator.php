<?php namespace Bozboz\Admin\Decorators;

use Event, Str, Config;
use Bozboz\Admin\Models\Base;
use Bozboz\Admin\Models\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Fluent;

abstract class ModelAdminDecorator
{
	/**
	 * @var Bozboz\Admin\Models\Base
	 */
	protected $model;

	/**
	 * @param  Bozboz\Admin\Models\Base  $model
	 */
	public function __construct(Base $model)
	{
		$this->model = $model;
	}

	/**
	 * Return the label identifying the instance
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return mixed
	 */
	abstract public function getLabel($instance);

	/**
	 * Return the fields displayed on a create/edit screen
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return array
	 */
	abstract public function getFields($instance);

	/**
	 * Return the columns to be displayed on an overview screen
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return array
	 */
	public function getColumns($instance)
	{
		return [
			'Name' => $this->getLabel($instance)
		];
	}

	/**
	 * DEPRECATED: Retrieve $this->model
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
		$name = preg_replace('/([a-z])([A-Z])/', '$1 $2', class_basename($this->model));
		return $plural ? Str::plural($name) : $name;
	}

	/**
	 * Apply each defined listing filter to the passed $builder
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $query
	 * @return void
	 */
	protected function filterListingQuery(Builder $query)
	{
		foreach($this->getListingFilters() as $listingFilter) {
			$listingFilter->filter($query);
		}
	}

	/**
	 * Add order by clause to the $query, if model is sortable; or order by
	 * latest if model uses timestamps
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $query
	 * @return void
	 */
	protected function modifyListingQuery(Builder $query)
	{
		if ($this->isSortable()) {
			$query->orderBy($this->model->sortBy());
		} elseif ($this->model->usesTimestamps()) {
			$query->orderBy($this->model->getTable() . '.created_at', 'DESC');
		}
	}

	/**
	 * Retrieve a paginated collection of instances of $this->model to display
	 *
	 * @return Illuminate\Pagination\Paginator
	 */
	public function getListingModels()
	{
		return $this->getModelQuery()->paginate($this->listingPerPageLimit());
	}

	/**
	 * Determine number of items per page on the listing
	 *
	 * @return int
	 */
	protected function listingPerPageLimit()
	{
		return Input::get('per-page', Config::get('admin::listing_items_per_page'));
	}

	/**
	 * Retrieve entire collection of instances of $this->model to display
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function getListingModelsNoLimit()
	{
		return $this->getModelQuery()->get();
	}

	/**
	 * Get filtered, customised query builder object for $this->model
	 *
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	protected function getModelQuery()
	{
		$query = $this->model->newQuery();

		$this->filterListingQuery($query);

		$this->modifyListingQuery($query);

		return $query;
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
		$fieldsObj = new Fluent(array_filter($this->getFields($instance)));

		// Below line deprecated in v1.1.0.
		// Flagged for removal in next major version
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
	 * Get the names of many-to-many relationships defined on the model that
	 * should be synced.
	 *
	 * @return array
	 */
	public function getSyncRelations()
	{
		return [];
	}

	/**
	 * Get the names (and associated attribute to use) of list-style
	 * many-to-many relationship on the model that should be saved.
	 *
	 * @return array
	 */
	public function getListRelations()
	{
		return [];
	}

	/**
	 * Set the related IDs as an attribute on the $instance.
	 *
	 * @deprecated
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return void
	 */
	public function injectSyncRelations(Base $instance)
	{
		$this->injectRelations($instance);
	}

	/**
	 * Set the related IDs as an attribute on the $instance.
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @return void
	 */
	public function injectRelations(Base $instance)
	{
		foreach ($this->getSyncRelations() as $relationName) {
			$instance->setAttribute(
				$relationName . '_relationship',
				$instance->$relationName()->getRelatedIds()
			);
		}

		foreach($this->getListRelations() as $relationName => $attribute) {
			$instance->setAttribute(
				$relationName . '_relationship',
				$instance->$relationName()->lists($attribute)
			);
		}
	}

	/**
	 * Update the many-to-many relationship mappings after a form submission.
	 *
	 * @deprecated
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @param  array  $formInput
	 * @return void
	 */
	public function updateSyncRelations(Base $instance, $formInput)
	{
		$this->updateRelations($instance, $formInput);
	}

	/**
	 * Update the many-to-many relationship mappings after a form submission.
	 *
	 * @param  Bozboz\Admin\Models\Base  $instance
	 * @param  array  $formInput
	 * @return void
	 */
	public function updateRelations(Base $instance, $formInput)
	{
		foreach ($this->getSyncRelations() as $relationship) {
			if (isset($formInput[$relationship . '_relationship'])) {
				$data = array_filter($formInput[$relationship . '_relationship']);
				$instance->$relationship()->sync(is_array($data) ? $data : array());
			}
		}

		foreach ($this->getListRelations() as $relationship => $attribute) {
			if (isset($formInput[$relationship . '_relationship'])) {
				$data = array_filter($formInput[$relationship . '_relationship']);

				$relation = $instance->$relationship();
				$model = $relation->getModel();

				$toSync = array_map(function($value) use ($model, $attribute) {
					return $model->firstOrCreate([
						$attribute => $value
					])->id;
				}, $data);

				$relation->sync($toSync);
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

	/**
	 * Determine if underlying model is sortable
	 *
	 * @return boolean
	 */
	public function isSortable()
	{
		return $this->model instanceof Sortable;
	}

	/**
	 * Return a string to identify the underlying model on the listing screen
	 *
	 * @return string
	 */
	public function getListingIdentifier()
	{
		return get_class($this->model);
	}
}
