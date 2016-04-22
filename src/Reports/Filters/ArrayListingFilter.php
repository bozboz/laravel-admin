<?php

namespace Bozboz\Admin\Reports\Filters;

class ArrayListingFilter extends AbstractSelectFilter
{
	protected $options;

	public function __construct($name, $options, $callback = null, $default = null)
	{
		parent::__construct($name, $callback, $default);

		$this->options = $options;
	}

	protected function getOptions()
	{
		return $this->options;
	}
}
