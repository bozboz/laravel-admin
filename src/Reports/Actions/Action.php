<?php

namespace Bozboz\Admin\Reports\Actions;

use Bozboz\Admin\Reports\Actions\Presenters\Presenter;

class Action
{
	protected $instance;
	private $presenter;
	private $permission;

	public function __construct(Presenter $presenter, $permission = null)
	{
		$this->presenter = $presenter;
		$this->permission = $permission ?: new Permissions\Valid;
	}

	/**
	 * Check to see if an action can be rendered, and if so, output it
	 *
	 * @return mixed
	 */
	public function render()
	{
		try {
			if ($this->check()) {
				return $this->output();
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Check permission to see if action can be viewed
	 *
	 * @return boolean
	 */
	public function check()
	{
		return $this->permission->check($this->instance);
	}

	/**
	 * Delegate to the presenter to render the output for the action
	 *
	 * @return mixed
	 */
	protected function output()
	{
		return $this->presenter->render();
	}

	/**
	 * Store the incoming instance to be applied to the action, and also send to
	 * the presenter to use, for example, when generating a URL.
	 *
	 * @param  Illuminate\Database\Eloquent\Model  $instance
	 * @return self
	 */
	public function setInstance($instance)
	{
		$this->instance = $instance;

		$this->presenter->setInstance($instance);

		return $this;
	}
}
