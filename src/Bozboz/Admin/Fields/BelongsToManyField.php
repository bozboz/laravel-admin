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
	protected $decorator;

	protected $relationship;

	protected $callback;

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
		$html .= '<select class="select2 form-control" multiple name="' . $name . '[]">';
		
		$relatedModels = $this->relationship->get();

		foreach ($this->generateQueryBuilder()->get() as $i => $model) {
			$html .= '<option ' . ($relatedModels->contains($model) ? 'selected' : '') . ' value="' . $model->getKey() . '">' . $this->decorator->getLabel($model) . '</option>';
		}

		return $html . '</select>';
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel()
	{
		$name = $this->relationship->getRelationName() . '_relationship';

		return Form::label($name, $this->label ?: ucwords(str_replace('_', ' ', $this->relationship->getTable())));
	}

	/**
	 * Construct a new query builder based on relationship
	 *
	 * @return Illuminate\Database\Query\Builder
	 */
	protected function generateQueryBuilder()
	{
		$parentModel = $this->relationship->getParent();
		$relatedModel = $this->relationship->getRelated();
		$queryBuilder = $relatedModel->query();

		if ($parentModel->getKey() && $this->shouldExcludeParent($parentModel)) {
			$queryBuilder = $queryBuilder->where($parentModel->getKeyName(), '!=', $parentModel->getKey());
		}
		if (!is_null($this->callback)) {
			call_user_func($this->callback, $queryBuilder);
		}

		return $queryBuilder;
	}

	/**
	 * Determine whether parent ID should be excluded from query (in case of
	 * self-referencing relationships)
	 *
	 * @param  Illuminate\Database\Eloquent\Model  $parentModel
	 * @return boolean
	 */
	private function shouldExcludeParent($parentModel)
	{
		$relatedModel = $this->relationship->getRelated();

		return get_class($parentModel) === get_class($relatedModel);
	}
}
