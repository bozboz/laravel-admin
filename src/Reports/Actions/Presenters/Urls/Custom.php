<?php

namespace Bozboz\Admin\Reports\Actions\Presenters\Urls;

class Custom extends Url
{
	private $closure;

	public function __construct(callable $closure)
	{
		$this->closure = $closure;
	}

	public function compile($model)
	{
		return call_user_func($this->closure, $model);
	}
}
