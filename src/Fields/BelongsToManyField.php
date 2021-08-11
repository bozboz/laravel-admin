<?php namespace Bozboz\Admin\Fields;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Closure;
use Collective\Html\FormFacade as Form;
use Collective\Html\HtmlFacade as HTML;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
	public function __construct(ModelAdminDecorator $decorator, BelongsToMany $relationship, array $attributes = [], Closure $callback = null)
	{
		if (array_key_exists('name', $attributes)) {
			$name = $attributes['name'];
		} else {
			$name = $relationship->getRelationName() . '_relationship';
		}

		parent::__construct($name, $attributes);

		$this->decorator = $decorator;
		$this->relationship = $relationship;
		$this->callback = $callback;
	}

	/**
	 * Return a select2 component, with multiple select functionality
	 *
	 * @param  array  $params
	 * @return string
	 */
	public function getInput($params = [])
	{
		/**
		 * @var Collection $selected
		 */
		$selected = collect(Form::getValueAttribute($this->name))->map(function ($value) {
			if (intval($value) > 0) {
				return intval($value);
			}
			return $value;
		});
		$options = $this->generateQueryBuilder()->get()->sort(function ($a, $b) use ($selected) {
			return $selected->search($a->id) <=> $selected->search($b->id);
		})->map(function ($inst) use ($selected) {
			return (string)Form::getSelectOption(
				$this->decorator->getLabel($inst),
				$this->key ? $inst->{$this->key} : $inst->getKey(),
				$selected->all()
			);
		})->implode(PHP_EOL);

		$this->class .= ' select2';

		$attributes = HTML::attributes(array_merge($this->getInputAttributes(), [
			'name' => $this->name . '[]',
			'multiple' => 'multiple',
		]));

		return <<<HTML
			<input name="{$this->name}" type="hidden" id="{$this->name}">
			<select{$attributes}>
				{$options}
			</select>
HTML;
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel()
	{
		$label = ucwords(str_replace('_', ' ', snake_case($this->relationship->getRelationName())));

		return Form::label($this->name, $this->label ?: $label);
	}

	/**
	 * Get the list of attrbitues that shouldn't be added to the input
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return array_merge(parent::getUnsafeAttributes(), [
			'decorator',
			'relationship',
			'callback',
		]);
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
