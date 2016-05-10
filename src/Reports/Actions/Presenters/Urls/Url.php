<?php

namespace Bozboz\Admin\Reports\Actions\Presenters\Urls;

class Url implements Contract
{
	private $url;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function compile($instance)
	{
		return $this->url;
	}
}
