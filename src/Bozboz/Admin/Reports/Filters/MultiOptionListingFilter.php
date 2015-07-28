<?php

namespace Bozboz\Admin\Reports\Filters;

use Form;
use Input;

class MultiOptionListingFilter extends ListingFilter
{
	protected $options;

	public function __construct($name, $options)
	{
		$this->options = $options;

		parent::__construct($name);
	}

	protected function defaultFilter($field)
	{
		return function($builder, $value) use ($field)
		{
			$builder->whereHas($this->name, function($q) use ($value) {
				$q->whereIn('id', $value);
			});
		};
	}

	public function __toString()
	{
		$label = Form::label($this->name);

		$options = Form::select($this->name . '[]', $this->options, $this->getValue(), [
			'class' => 'select2',
			'multiple',
			'style' => 'min-width: 150px'
		]);

		$submit = Form::submit('Filter', ['class' => 'btn btn-sm btn-default']);

		return <<<HTML
			{$label}

			<div class="input-group">
				{$options}
				<div class="input-group-btn">
					{$submit}
				</div>
			</div>
HTML;
	}
}
