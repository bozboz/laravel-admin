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
	 * Retrieve sorting value of current instance
	 *
	 * @return int
	 */
	protected function getSortingValue()
	{
		return $this->getAttribute($this->sortBy());
	}

	/**
	 * Set sorting value on current instance
	 *
	 * @param  int  $value
	 */
	protected function setSortingValue($value)
	{
		$this->setAttribute($this->sortBy(), $value);
	}

	/**
	 * Modify sorting query before resorting rows if table contains more than
	 * one sorted list.
	 *
	 * @param  Illuminate\Database\Eloquent\Builder  $query
	 * @param  Bozboz\Admin\Base\Model  $instance
	 */
	public function scopeModifySortingQuery($query, $instance) {}

	/**
	 * Calculate sorting for a new row if nothing already set
	 *
	 * @param  Bozboz\Admin\Base\Sorting\Sortable  $instance
	 */
	public static function resortRowsCreated(Sortable $instance)
	{
		if ( ! $instance->getSortingValue()) {
			$query = $instance->newQuery()->modifySortingQuery($instance);

			if ($instance->sortPrependOnCreate()) {
				$query->incrementSort();
				$instance->setSortingValue(1);
			} else {
				$sortVal = $query->max($instance->sortBy()) + 1;
				$instance->setSortingValue($sortVal);
			}

			$instance->save();
		}
	}

	/**
	 * If true then new rows will be prepended to the sorting, if false appended
	 *
	 * @return boolean
	 */
	protected function sortPrependOnCreate()
	{
		return true;
	}

	/**
	 * Re-sort rows after one has been deleted
	 *
	 * @param  Bozboz\Admin\Base\Sorting\Sortable  $instance
	 */
	public static function resortRowsDeleted(Sortable $instance)
	{
		$instance->newQuery()
		         ->modifySortingQuery($instance)
		         ->where($instance->sortBy(), '>', $instance->getSortingValue())
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
		$from = $this->getSortingValue();
		$to = null;

		// if the node has a sibling before it, insert after it
		if ($before) {
			$before = $this->find($before)->getSortingValue();
			$to = $before + ($from > $before ? 1 : 0);
		}

		// if the node has a sibling after it, insert before it
		if ($after) {
			$after = $this->find($after)->getSortingValue();
			$to = $after - ($from < $after ? 1 : 0);
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
		$from = $this->getSortingValue();

		$difference = $from - $to;

		if ($difference) {
			$query = $this->newQuery()->modifySortingQuery($this)->whereBetween(
				$this->sortBy(),
				[min($from, $to), max($from, $to)]
			);

			if ($difference > 1) {
				$query->incrementSort();
			} else {
				$query->decrementSort();
			}
			$this->setSortingValue($to);
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