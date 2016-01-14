<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Handle sorting of a nested set.
 * Designed to work with kalnoy/nestedset
 * https://github.com/lazychaser/laravel-nestedset
 */
trait NestedSortableTrait
{
	protected $retrySort = true;

	/**
	 * Re-sort item based on either before/after siblings or parent.
	 * If the first runthrough fails, run fixTree and retry
	 * @param  int $before
	 * @param  int $after
	 * @param  int $parent
	 */
	public function sort($before, $after, $parent)
	{
		try {
			// if the node has a sibling before it, insert after it
			if ($before) {
				$this->insertAfter($this->find($before));
				return 'Insert after #' . $before;
			}

			// if the node has a sibling after it, insert before it
			if ($after) {
				$this->insertBefore($this->find($after));
				return 'Insert before #' . $after;
			}

			// otherwise, if the node has a parent, append to it
			if ($parent) {
				$this->find($parent)->append($this);
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