<?php namespace Bozboz\Admin\Fields;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Closure;
use Collective\Html\FormFacade as Form;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToField extends Field
{
	/**
	 * @param  Bozboz\Admin\Base\ModelAdminDecorator  $decorator
	 * @param  Illuminate\Database\Eloquent\Relations\BelongsTo  $relationship
	 * @param  array  $attributes
	 * @param  Closure  $callback
	 */
	public function __construct(ModelAdminDecorator $decorator, BelongsTo $relation, array $attributes = [], Closure $callback = null)
	{
		parent::__construct($attributes);

		if ( ! $this->name) {
			$this->name = $relation->getForeignKey();
		}

		$this->decorator = $decorator;
		$this->relation = $relation;
		$this->callback = $callback ?: function($query) {};
	}

	/**
	 * Render an HTML select consisting of related models
	 *
	 * @return string
	 */
	public function getInput()
	{
		$all = $this->generateQueryBuilder()->get();
		$options = ['' => 'Select'];

		$selected = $this->relation->first();

		if ($selected && ! $all->contains($selected)) {
			$all->push($selected);
		}

		foreach ($all as $model) {
			$options[$model->getKey()] = $this->decorator->getLabel($model);
		}

		$this->class .= ' form-control select-2';

		return Form::select($this->name, $options, null, $this->getInputAttributes());
	}

	/**
	 * Generate a query builder instance from the relation
	 *
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	protected function generateQueryBuilder()
	{
		$queryBuilder = $this->relation->getRelated()->newQuery();

		call_user_func($this->callback, $queryBuilder);

		return $queryBuilder;
	}

	/**
	 * Render an HTML label for the relation
	 *
	 * @return string
	 */
	public function getLabel()
	{
		$defaultLabel = function() {
			return ucwords(str_replace('_', ' ', str_replace('_id', '', $this->relation->getForeignKey())));
		};

		return Form::label($this->relation->getForeignKey(), $this->label ?: $defaultLabel());
	}
}

