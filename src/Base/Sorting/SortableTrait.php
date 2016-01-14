<?php

namespace Bozboz\Admin\Base\Sorting;

trait SortableTrait
{
	static public function bootSortableTrait()
	{
		static::created([static::class, 'resortRowsCreated']);
		static::deleted([static::class, 'resortRowsDeleted']);
	}

	/**
	 * Modify sorting query before resorting rows if table contains more than
	 * one sorted list.
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $query
	 * @param  Bozboz\Admin\Base\Model  $instance
	 */
	protected function scopeModifySortingQuery($query, $instance) {}

	/**
	 * Calculate sorting for a new row if nothing already set
	 *
	 * @param  Bozboz\Admin\Base\Sorting\Sortable  $instance
	 */
	public function resortRowsCreated(Sortable $instance)
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
	 *
	 * @param  Bozboz\Admin\Base\Sorting\Sortable  $instance
	 */
	public function resortRowsDeleted(Sortable $instance)
	{
		$sortBy = $instance->sortBy();

		$instance->newQuery()
		         ->modifySortingQuery($instance)
		         ->where($sortBy, '>', $instance->$sortBy)
		         ->decrementSort();
	}

	/**
	 * Re-sort item based on a sibling either before or after it
	 *
	 * @param  int  $before
	 * @param  int  $after
	 * @param  int  $parent
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
	 *
	 * @param  int  $to
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
	 * Increment sort values on a query
	 *
	 * @param  Illuminate\Database\Eloquent\Builder $query
	 */
	public function scopeIncrementSort($query)
	{
		// Increment is called on the raw query builder to avoid touching the
		// updated timestamp
		$query->getQuery()->increment($this->sortBy());
	}

	/**
	 * Decrement sort values on a query
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $query
	 */
	public function scopeDecrementSort($query)
	{
		// Decrement is called on the raw query builder to avoid touching the
		// updated timestamp
		$query->getQuery()->decrement($this->sortBy());
	}
}
