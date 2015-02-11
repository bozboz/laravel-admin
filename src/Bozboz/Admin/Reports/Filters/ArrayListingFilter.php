<?php namespace Bozboz\Admin\Reports\Filters;

use Illuminate\Support\Facades\Form;

class ArrayListingFilter extends ListingFilter
{
	protected function defaultFilter($field)
	{
		return function($builder, $value) use ($field)
		{
			if ($value) {
				$builder->where($field, $value);
			}
		};
	}

	public function __toString()
	{
		$html = Form::label($this->name);
		$html .= Form::select($this->name, $this->options, null, ['onChange' => 'this.form.submit()', 'class' => 'form-control']);

		return $html;
	}
}
