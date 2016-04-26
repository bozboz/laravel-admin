<?php

namespace Bozboz\Admin\Reports\Filters;

use Collective\Html\FormFacade as Form;

abstract class AbstractSelectFilter extends ListingFilter
{
	protected $default;

	public function __construct($name, $callback = null, $default = null)
	{
		parent::__construct($name, $callback);

		$this->default = $default;
	}

	public function __toString()
	{
		$html = Form::label($this->name);
		$html .= Form::select($this->name, $this->getOptions(), $this->getValue(), [
			'onChange' => 'this.form.submit()',
			'class' => 'form-control select2'
		]);

		return $html;
	}

	public function getValue()
	{
		return parent::getValue() ?: $this->default;
	}

	abstract protected function getOptions();
}
