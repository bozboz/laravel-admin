<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Database\QueryException;
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
			// The nested set package has a bug when trying to move a node to
			// where it already is causing it to set the _lft and _rgt values to
			// null. Set the DB mode to strict so that it won't allow setting
			// the columns to null.
			DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES';");

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
		catch (QueryException $e) {
			DB::rollback();

			// The nested set package has a bug when trying to move a node to
			// where it already is causing it to set the _lft and _rgt values to
			// null. If they're both null here then that's likely what's
			// happened so don't need to care.
			// If they're not null then who knows what happened, rethrow.
			if ( ! is_null($this->_lft) && ! is_null($this->_rgt)) {
				throw $e;
			}
		}
	}
}
