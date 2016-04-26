<?php namespace Bozboz\Admin\Reports\Filters;

use Illuminate\Html\FormFacade as Form;

class SearchListingFilter extends ListingFilter
{
	/**
	 * Parse argument and return a callback condition in which to filter on
	 *
	 * @param  array|string|null|callable  $input
	 * @throws InvalidArgumentException
	 * @return Closure
	 */
	protected function parseCallback($input)
	{
		if (is_array($input)) {
			return $this->defaultFilter($input);
		} elseif (is_string($input)) {
			return $this->defaultFilter([$input]);
		} elseif (is_null($input)) {
			return $this->defaultFilter([$this->name]);
		} elseif (is_callable($input)) {
			return $input;
		}

		throw new InvalidArgumentException(
			'The second argument to ' . __CLASS__ . ' ' .
			'should be a callback, an array, a string or ommitted entirely'
		);
	}

	/**
	 * Return a callable function, containing the default search filtering
	 * logic
	 *
	 * @param  array  $attributes
	 * @return Closure
	 */
	protected function defaultFilter($attributes)
	{
		return function($builder, $value) use ($attributes)
		{
			$builder->where(function ($query) use ($value, $attributes) {
				foreach ($attributes as $attribute) {
					$query->orWhere($attribute, 'LIKE', '%' . $value . '%');
				}
			});
		};
	}

	/**
	 * Render an input and a submit button
	 *
	 * @return string
	 */
	public function __toString()
	{
		$label = Form::label($this->name);
		$input = Form::text($this->name, $this->getValue(), ['class' => 'form-control input-sm']);
		$submit = Form::submit('Search', ['class' => 'btn btn-sm btn-default']);

		return <<<HTML
			{$label}
			<div class="input-group">
				{$input}
				<div class="input-group-btn">
					{$submit}
				</div>
			</div>
HTML;
	}
}
