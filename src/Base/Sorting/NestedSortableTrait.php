<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
			DB::beginTransaction();

			// if the node has a sibling before it, insert after it
			if ($before) {
				$this->insertAfterNode($this->find($before));
			}

			// if the node has a sibling after it, insert before it
			else if ($after) {
				$this->insertBeforeNode($this->find($after));
			}

			// otherwise, if the node has a parent, append to it
			else if ($parent) {
				$this->find($parent)->appendNode($this);
			}

			// if it has neither, it's the first root node.
			else {
				$this->saveAsRoot();
			}

			DB::commit();
		}
		catch (LogicException $e) {

			DB::rollback();

			if ($this->retrySort) {
				Log::warning('Nested sort error (retry): ' . $e->getMessage());

				$this->retrySort = false;

				$this->newNestedSetQuery()->fixTree();
				$this->sort($before, $after, $parent);
			} else {
				Log::error('Nested sort error (ABORT)');

				throw $e;
			}
		}
	}
}