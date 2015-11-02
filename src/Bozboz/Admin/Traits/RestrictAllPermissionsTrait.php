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

	protected function createPermissions($stack)
	{
		$stack->add($this->getRestrictRule());
	}

	protected function editPermissions($stack, $id)
	{
		$stack->add($this->getRestrictRule(), $id);
	}

	protected function deletePermissions($stack, $id)
	{
		$stack->add($this->getRestrictRule(), $id);
	}
}
