<?php namespace Bozboz\Admin\Reports\Filters;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelationFilter extends AbstractSelectFilter
{
	protected $decorator;
	protected $relation;
	protected $query;

	public function __construct(BelongsTo $relation, ModelAdminDecorator $decorator = null, Builder $query = null, $default = null)
	{
		// It would be nice to be able to call $relation->getRelationName() here,
		// but this method doesn't exist (despite a $relation property existing).
		// Instead, we'll deconstruct the foreign key to get a reasonable name.
		$name = str_replace(['_id', '_'], ['', ' '], $relation->getForeignKey());

		$this->decorator = $decorator;
		$this->relation = $relation;
		$this->query = $query;

		parent::__construct($name, $relation->getForeignKey(), $default);
	}

	/**
	 * Get an array of instances based on a query of the related model,
	 * decorated by a decorator and modified by an optional query callback
	 *
	 * @return Illuminate\Support\Collection
	 */
	protected function getOptions()
	{
		$builder = $this->relation->getRelated();

		if ($this->query) call_user_func($this->query, $builder);

		return $builder->get()->keyBy('id')->map(function($instance) {
			return $this->decorator->getLabel($instance);
		})->prepend('All', '');
	}
}
