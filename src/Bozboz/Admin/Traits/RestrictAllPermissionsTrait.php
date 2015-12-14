<?php

namespace Bozboz\Admin\Traits;

trait RestrictAllPermissionsTrait
{
	/**
	 * Retrieve the name of the rule to restrict
	 *
	 * @return string
	 */
	abstract protected function getRestrictRule();

	protected function viewPermissions($stack)
	{
		$stack->add($this->getRestrictRule());
	}

	protected function createPermissions($stack, $instance)
	{
		$stack->add($this->getRestrictRule());
	}

	protected function editPermissions($stack, $instance)
	{
		$stack->add($this->getRestrictRule(), $instance);
	}

	protected function deletePermissions($stack, $instance)
	{
		$stack->add($this->getRestrictRule(), $instance);
	}
}
