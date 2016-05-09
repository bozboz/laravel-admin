<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

abstract class Presenter
{
	protected $label;
	protected $instance;

	protected $attributes = [
		'class' => '',
	];

	public function __construct($attributes = [])
	{
		foreach ($attributes as $key => $value) {
			$this->attributes[$key] = $value;
		}
	}

	public function render()
	{
		return view($this->getView(), $this->getViewData());
	}

	public function setInstance($instance)
	{
		$this->instance = $instance;
	}

	abstract public function getView();

	abstract public function getViewData();
}
