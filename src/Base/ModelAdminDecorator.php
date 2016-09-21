<?php

namespace Bozboz\Admin\Base;

use Bozboz\Admin\Base\ModelInterface;
use Bozboz\Admin\Base\Sortable as DeprecatedSortable;
use Bozboz\Admin\Exceptions\Deprecated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

abstract class ModelAdminDecorator
{
	/**
	 * @var Bozboz\Admin\Base\ModelInterface
	 */
	protected $model;

	/**
	 * @param  Bozboz\Admin\Base\ModelInterface  $model
	 */
	public function __construct(ModelInterface $model)
	{
		$this->model = $model;
	}

	/**
	 * Return the label identifying the instance
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return mixed
	 */
	abstract public function getLabel($instance);

	/**
	 * Return the fields displayed on a create/edit screen
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return array
	 */
	abstract public function getFields($instance);

	/**
	 * Return the columns to be displayed on an overview screen
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return array
	 */
	public function getColumns($instance)
	{
		return [
			'Name' => $this->getLabel($instance)
		];
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
		return $plural ? str_plural($name) : $name;
	}

	/**
	 * Retrieve a more specific heading for a particular instance
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return string
	 */
	public function getHeadingForInstance($instance)
	{
		return $this->getHeading(false);
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
	 * @deprecated
	 *
	 * Retrieve a full or paginated collection of instances of $this->model
	 *
	 * @param  boolean  $limit
	 * @return Illuminate\Pagination\Paginator
	 */
	public function getListingModels()
	{
		throw new Deprecated(sprintf(
			'%s is deprecated. Use either %s or %s',
			__FUNCTION__, 'getListingModelsPaginated', 'getListingModelsNoLimit'
		));
	}

	/**
	 * Retrieve a paginated collection of instances of $this->model
	 *
	 * @param  boolean  $limit
	 * @return Illuminate\Pagination\Paginator
	 */
	public function getListingModelsPaginated($limit = null)
	{
		return $this->getModelQuery()->paginate(
			$limit ?: $this->listingPerPageLimit()
		);
	}

	/**
	 * Determine number of items per page on the listing
	 *
	 * @return int
	 */
	protected function listingPerPageLimit()
	{
		return Config::get('admin.listing_items_per_page');
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
	 * Retrieve results from a query in chunks
	 *
	 * @param  int  $amount
	 * @param  Callable  $callback
	 * @return void
	 */
	public function getListingModelsChunked($amount, $callback)
	{
		$this->getModelQuery()->chunk($amount, $callback);
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
	 * Get and array of options for the items per page select on listing view
	 *
	 * @return array
	 */
	public function getItemsPerPageOptions()
	{
		$perPage = $this->listingPerPageLimit();
		$range = range($perPage, $perPage*4, $perPage);
		return array_combine($range, $range);
	}

	/**
	 * Build an array of fields
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return array
	 */
	public function buildFields($instance)
	{
		return array_filter($this->getFields($instance));
	}

	/**
	 * @param  array  $attributes
	 * @return Bozboz\Admin\Base\ModelInterface
	 */
	public function newModelInstance($attributes = array())
	{
		return $this->model->newInstance();
	}

	/**
	 * @param  int  $id
	 * @return Bozboz\Admin\Base\ModelInterface
	 */
	public function findInstance($id)
	{
		return $this->model->findOrFail($id);
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
	 * Get the names of the sortable many-to-many relationships on the model
	 * return array
	 */
	public function getSortableSyncRelations()
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
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @return void
	 */
	public function injectRelations($instance)
	{
		foreach (array_merge($this->getSyncRelations(), $this->getSortableSyncRelations()) as $relationName) {
			$instance->setAttribute(
				$relationName . '_relationship',
				$instance->$relationName()->getRelatedIds()->all()
			);
		}

		foreach($this->getListRelations() as $relationName => $attribute) {
			$instance->setAttribute(
				$relationName . '_relationship',
				$instance->$relationName()->lists($attribute)->all()
			);
		}
	}

	/**
	 * Update the many-to-many relationship mappings after a form submission.
	 *
	 * @param  Bozboz\Admin\Base\ModelInterface  $instance
	 * @param  array  $formInput
	 * @return void
	 */
	public function updateRelations($instance, $formInput)
	{
		$sync = [];

		foreach($this->getSyncRelations() as $relationship) {
			$sync[$relationship] = function($data) {
				return $data;
			};
		}

		foreach($this->getSortableSyncRelations() as $relationship) {
			$sync[$relationship] = function($data) {
				$syncData = [];
				foreach($data as $i => $value) {
					$syncData[$value] = [
						'sorting' => $i
					];
				}
				return $syncData;
			};
		}

		foreach ($this->getListRelations() as $relationship => $attribute) {
			$sync[$relationship] = function($data, $relation) use ($attribute) {
				$model = $relation->getModel();
				return array_map(function($value) use ($model, $attribute) {
					return $model->firstOrCreate([
						$attribute => $value
					])->id;
				}, $data);
			};
		}

		foreach($sync as $relationship => $callback) {
			if (isset($formInput[$relationship . '_relationship'])) {
				$relation = $instance->$relationship();
				$data = $callback(array_filter((array)$formInput[$relationship . '_relationship']), $relation);
				$relation->sync($data);
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
		return $this->model instanceof \Bozboz\Admin\Base\Sorting\Sortable || $this->model instanceof DeprecatedSortable;
	}

	/**
	 * Determine if underlying model is sortable
	 *
	 * @return boolean
	 */
	public function isDeprecatedSortable()
	{
		return $this->model instanceof DeprecatedSortable;
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
