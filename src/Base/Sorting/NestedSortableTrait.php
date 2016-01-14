<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Support\Facades\DB;
use LogicException;

trait NestedSortableTrait
{
	protected $retrySort = true;

	// static public function bootSortableTrait()
	// {
	// 	static::created([new static, 'calculateSorting']);
	// }

	// protected function scopeModifySortingQuery($query, $instance)
	// {
	// }

	// public function calculateSorting($instance)
	// {
	// 	$instance->saveAsRoot();
	// }

	public function sort($before, $after, $parent)
	{
		$factory = (new self);

		try {
			// if the node has a sibling before it, insert after it
			if ($before) {
				$this->insertAfter($factory->find($before));
				return 'Insert after #' . $before;
			}

			// if the node has a sibling after it, insert before it
			if ($after) {
				$this->insertBefore($factory->find($after));
				return 'Insert before #' . $after;
			}

			// otherwise, if the node has a parent, append to it
			if ($parent) {
				$factory->find($parent)->append($this);
				return 'Append to parent #' . $parent;
			}

			// if it has neither, it's the first root node.
			$this->saveAsRoot();
			return 'Save as root';
		}
		catch (LogicException $e) {
			if ($this->retrySort) {
				$this->retrySort = false;

				$this->fixTree();
				$this->sort($before, $after, $parent);
			} else {
				throw $e;
			}
		}
	}
}