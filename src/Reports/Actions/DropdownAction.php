<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Presenters\Dropdown;

class DropdownAction extends Action
{
	protected $items;
	protected $validItems;

	function __construct($items, $label, $icon = null, $attributes = [])
	{
		$this->items = collect($items);
		$this->validItems = collect();
		$this->label = $label;
		$this->icon = $icon;
		$this->attributes = $attributes;
	}

	/**
	 * Set the instance on each of the drop down items
	 *
	 * @param  Illuminate\Database\Eloquent\Model  $instance
	 * @return void
	 */
	public function setInstance($instance)
	{
		$this->instance = $instance;

		$this->items->filter(function($action) {
			$action->setInstance($this->instance);
		});
	}

	/**
	 * Determine if the dropdown has any valid items
	 *
	 * @return boolean
	 */
	public function check()
	{
		$this->validItems = $this->items->filter(function($action) {
			return $action->check($this->instance);
		});

		return ! $this->validItems->isEmpty();
	}

	/**
	 * If the dropdown only has one valid item, output that, otherwise output a
	 * full dropdown
	 *
	 * @return mixed
	 */
	public function output()
	{
		if ($this->onlyContainsSingleItem()) {
			return $this->outputFirstItem();
		}

		return $this->outputDropdown();
	}

	/**
	 * Determine if the dropdown only contains a single item
	 *
	 * @return boolean
	 */
	private function onlyContainsSingleItem()
	{
		return $this->validItems->count() === 1;
	}

	/**
	 * Output the first of the dropdown's valid items
	 *
	 * @return mixed
	 */
	private function outputFirstItem()
	{
		return $this->validItems->first()->output();
	}

	/**
	 * Instantiate and render a dropdown presenter consisting of its valid items
	 *
	 * @return mixed
	 */
	private function outputDropdown()
	{
		$presenter = new Dropdown($this->validItems, $this->label, $this->icon, $this->attributes);

		return $presenter->render();
	}
}
