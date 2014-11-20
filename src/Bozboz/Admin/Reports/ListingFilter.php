<?php namespace Bozboz\Admin\Reports;

use Closure;
use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\Input;

class ListingFilter
{
	public function __construct($name, $options, Closure $callback = null)
	{
		$this->name = $name;
		$this->options = $options;
		$this->callback = $callback ?: $this->defaultFilter();
	}

	public function filter($builder)
	{
		call_user_func($this->callback, $builder, Input::get($this->name));
	}

	private function defaultFilter()
	{
		$name = $this->name;

		return function($builder, $value) use ($name) {
			if ($value) {
				$builder->where($name, $value);
			}
		};
	}

	public function __toString()
	{
		return 
			Form::label($this->name) .
			Form::select($this->name, $this->options, null, ['onChange' => 'this.form.submit()']);
	}
}
