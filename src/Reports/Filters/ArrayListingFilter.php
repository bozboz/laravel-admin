<?php namespace Bozboz\Admin\Reports\Filters;

use Illuminate\Html\FormFacade as Form;

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
		return parent::getValue() ?: $this->default;
	}

}
