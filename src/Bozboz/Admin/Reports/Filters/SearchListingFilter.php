<?php namespace Bozboz\Admin\Reports\Filters;

use Form;

class SearchListingFilter extends ListingFilter
{
	protected $attributes;

	public function __construct($name, array $attributes = [], $callback = null)
	{
		parent::__construct($name, $callback);

		$this->attributes = $attributes ?: [$name];
	}

	protected function defaultFilter($field)
	{
		return function($builder, $value) use ($field)
		{
			$builder->where(function ($query) use ($value) {
				foreach ($this->attributes as $attribute) {
					$query->orWhere($attribute, 'LIKE', '%' . $value . '%');
				}
			});
		};
	}

	public function __toString()
	{
		$html = Form::label($this->name);
		$html .= Form::text($this->name, $this->getValue());
		$html .= Form::submit('Search');

		return $html;
	}
}
