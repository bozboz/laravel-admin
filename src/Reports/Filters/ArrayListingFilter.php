<?php namespace Bozboz\Admin\Reports\Filters;

use Illuminate\Html\FormFacade as Form;
use Illuminate\Support\Facades\Input;

class ArrayListingFilter extends ListingFilter
{
	protected $options;

	protected $default;

	public function __construct($name, $options, $callback = null, $default = null)
	{
		parent::__construct($name, $callback);

		$this->options = $options;
		$this->default = $default;
	}

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
		$html .= Form::select($this->name, $this->options, $this->getValue(), [
			'onChange' => 'this.form.submit()',
			'class' => 'form-control select2'
		]);

		return $html;
	}

	public function getValue()
	{
		return Input::get($this->name, $this->default);
	}
}
