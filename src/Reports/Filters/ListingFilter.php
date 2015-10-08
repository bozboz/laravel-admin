<?php namespace Bozboz\Admin\Reports\Filters;

use Closure;
use InvalidArgumentException;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;

abstract class ListingFilter
{
	protected $name;
	protected $callback;

	/**
	 * Get a default closure which filters the builder on the provided $field
	 *
	 * @param  string  $field
	 * @return Closure
	 */
	abstract protected function defaultFilter($field);

	/**
	 * Render the HTML for the filter
	 *
	 * @return string
	 */
	abstract public function __toString();

	/**
	 * @param  string  $name
	 * @param  string|Closure  $callback
	 */
	public function __construct($name, $callback = null)
	{
		$this->name = $name;
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
		$value = $this->getValue();

		if ( ! is_null($value)) {
			$this->call($builder, $value);
		}
	}

	/**
	 * Execute the callback, with given arguments
	 *
	 * @return mixed
	 */
	public function call()
	{
		return call_user_func_array($this->callback, func_get_args());
	}

	/**
	 * Parse provided $callback and return a Closure
	 *
	 * @param  mixed  $callback
	 * @throws InvalidArgumentException
	 * @return Closure
	 */
	protected function parseCallback($callback)
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
	 * Get value based on current input, or fall back to default
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return Input::get($this->name);
	}
}
