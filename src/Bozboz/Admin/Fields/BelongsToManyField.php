<?php namespace Bozboz\Admin\Fields;

use Closure;
use Illuminate\Support\Facades\Form;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Bozboz\Admin\Decorators\ModelAdminDecorator;

/**
 * Render HTML that represents a Illuminate\Database\Eloquent\Relations\BelongsToMany instance
 */
class BelongsToManyField extends Field
{
	private $decorator;

	private $relationship;

	private $callback;

	/**
	 * @param ModelAdminDecorator $decorator The decorator used to render the model instance
	 * @param BelongsToMany $relationship Relationship between the model instance and respective model type
	 * @param array $attributes Fluent attributes
	 * @param Closure Influences which model instances are presented as candidates for a relationship
	 */
	public function __construct(ModelAdminDecorator $decorator, BelongsToMany $relationship, array $attributes, Closure $callback = null)
	{
		parent::__construct($attributes);

		$this->decorator = $decorator;
		$this->relationship = $relationship;
		$this->callback = $callback;
	}

	/**
	 * @return string HTML representing the relationship
	 */
	public function getInput($params = [])
	{
		$name = $this->relationship->getRelationName() . '_relationship';
		$html = sprintf('<input name="%1$s" type="hidden" id="%1$s">', $name);

		$relatedModels = $this->relationship->get();
		foreach ($this->generateQueryBuilder()->get() as $model) {
			$id = $name . '[' . $model->getKey() . ']';
			$checkbox = Form::checkbox(
				$name . '[]',
				$model->getKey(),
				$relatedModels->contains($model),
				['id' => $id]
			);

			$html .= '<label class="checkbox">' . $checkbox . ' ' . $this->decorator->getLabel($model) . '</label>';
		}

		return $html;
	}

	/**
	 * @returns Builder An Eloquent query builder
	 */
	private function generateQueryBuilder()
	{
		$relatedModel = $this->relationship->getRelated();
		$queryBuilder = $relatedModel->query();

		if ($this->shouldExcludeParent()) {
			$parentModel = $this->relationship->getParent();
			$queryBuilder = $queryBuilder->where($parentModel->getKeyName(), '!=', $parentModel->getKey());
		}
		if (!is_null($this->callback)) {
			$callback = $this->callback;
			$queryBuilder = $callback($queryBuilder);
		}

		return $queryBuilder;
	}

	/**
	 * This becomes a factor when working with self-referencing relationships
	 * (e.g. a case study being related to similar case studies).
	 *
	 * @return boolean
	 */
	private function shouldExcludeParent()
	{
		$parentModel = $this->relationship->getParent();
		$relatedModel = $this->relationship->getRelated();

		return $parentModel->exists() && get_class($parentModel) === get_class($relatedModel);
	}
}
