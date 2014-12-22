<?php namespace Bozboz\Admin\Reports;

use Closure, InvalidArgumentException;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;

class ListingFilter
{
	/**
	 * @param  string  $name
	 * @param  mixed  $options
	 * @param  string|Closure  $callback
	 */
	public function __construct($name, $options, $callback = null)
	{
		$this->name = $name;
		$this->options = $options;
		$this->callback = $this->parseCallback($callback);
	}

	/**
	 * Run the closure on the provided $builder, passing in input
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function filter(Builder $builder)
	{
		call_user_func($this->callback, $builder, Input::get($this->name));
	}

	/**
	 * Parse provided $callback and return a Closure
	 * 
	 * @param  mixed  $callback
	 * @throws InvalidArgumentException
	 * @return Closure
	 */
	private function parseCallback($callback)
	{
		if (is_string($callback)) {
			return $this->defaultFilter($callback);
		} elseif (is_null($callback)) {
			return $this->defaultFilter($this->name);
		} elseif (is_callable($callback)) {
			return $callback;
		}

		throw new InvalidArgumentException(
			'$callback should be a string, callable or ommitted entirely'
		);
	}

	/**
	 * Get a default closure which filters the builder on the provided $field
	 *
	 * @param  string  $field
	 * @return Closure
	 */
	private function defaultFilter($field)
	{
		return function($builder, $value) use ($field)
		{
			if ($value) {
				$builder->where($field, $value);
			}
		};
	}

	/**
	 * Render the select box filter
	 *
	 * @return string
	 */
	public function __toString()
	{
		return 
			Form::label($this->name) .
			Form::select($this->name, $this->options, null, ['onChange' => 'this.form.submit()', 'class' => 'form-control']);
	}
}
