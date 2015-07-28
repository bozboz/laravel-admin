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

	public function getValue()
	{
		$value = parent::getValue();

		return $value !== '' ? $value : null;
	}
}
