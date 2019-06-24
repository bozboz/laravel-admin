<?php

namespace Bozboz\Admin\Reports\Filters;

use Form;
use Illuminate\Database\Eloquent\Builder;

class HiddenFilter extends ListingFilter
{
	protected $filter;

	public function __construct(ListingFilter $filter)
	{
		$this->filter = $filter;
	}

	public function filter(Builder $builder)
	{
		$this->filter->filter($builder);
	}

	public function __toString()
	{
		if ($this->filter->getValue()) {
			return Form::hidden($this->filter->name, $this->filter->getValue());
		}
	}
}
