<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Support\Facades\DB;

trait SortableTrait
{
	static public function bootSortableTrait()
	{
		static::created([new static, 'resortRowsCreated']);
		static::deleted([new static, 'resortRowsDeleted']);
	}

	/**
	 * Modify sorting query before resorting rows if table contains more than
	 * one sorted list.
	 * @param  Builder $query
	 * @param  Model $instance
	 */
	protected function scopeModifySortingQuery($query, $instance) {}

	/**
	 * Calculate sorting for a new row if nothing already set
	 * @param  Model $instance
	 */
	public function resortRowsCreated($instance)
	{
		$sortBy = $instance->sortBy();
		if (!$instance->$sortBy) {
			$instance->newQuery()
			         ->modifySortingQuery($instance)
			         ->incrementSort();

			$instance->$sortBy = 1;
			$instance->save();
		}
	}

	/**
	 * Re-sort rows after one has been deleted
	 * @param  Model $instance
	 */
	public function resortRowsDeleted($instance)
	{
		$sortBy = $instance->sortBy();
		$instance->newQuery()
		         ->modifySortingQuery($instance)
		         ->where($sortBy, '>', $instance->$sortBy)
		         ->decrementSort();
	}

	/**
	 * Re-sort item based on a sibling either before or after it
	 * @param  int $before instance ID
	 * @param  int $after instance ID
	 * @param  int $parent
	 */
	public function sort($before, $after, $parent)
	{
		$sortBy = $this->sortBy();
		$from = $this->$sortBy;
		$to = null;

		// if the node has a sibling before it, insert after it
		if ($before) {
			$before = $this->find($before);
			$to = $before->$sortBy + ($from > $before->$sortBy ? 1 : 0);
		}

		// if the node has a sibling after it, insert before it
		if ($after) {
			$after = $this->find($after);
			$to = $after->$sortBy - ($from < $after->$sortBy ? 1 : 0);
		}

		if ($to) {
			$this->moveMe($to);
		}
	}

	/**
	 * Move instance from one sorting position to another
	 * @param  int $to
	 */
	protected function moveMe($to)
	{
		$sortBy = $this->sortBy();

		$from = $this->$sortBy;

		$difference = $from - $to;

		if ($difference) {
			$query = $this->newQuery()->modifySortingQuery($this)->whereBetween($sortBy, [min($from, $to), max($from, $to)]);

			if ($difference > 1) {
				$query->incrementSort();
			} else {
				$query->decrementSort();
			}
			$this->$sortBy = $to;
			$this->save();
		}
	}

	/**
	 * Increment sort values on a query.
	 * Performs getQuery on the builder so as not to affect the updated_at
	 * @param  Builder $query
	 */
	public function scopeIncrementSort($query)
	{
		$query->getQuery()->increment($this->sortBy());
	}

	/**
	 * Decrement sort values on a query.
	 * Performs getQuery on the builder so as not to affect the updated_at
	 * @param  Builder $query
	 */
	public function scopeDecrementSort($query)
	{
		$query->getQuery()->decrement($this->sortBy());
	}
}