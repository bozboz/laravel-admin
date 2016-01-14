<?php

namespace Bozboz\Admin\Base\Sorting;

use Illuminate\Support\Facades\DB;

trait SortableTrait
{
	static public function bootSortableTrait()
	{
		static::creating([new static, 'calculateSorting']);
	}

	protected function scopeModifySortingQuery($query, $instance)
	{
	}

	public function calculateSorting($instance)
	{
		if (!$instance->$sortBy) {
			$factory = (new self);
			$sortBy = $factory->sortBy();
			$factory->modifySortingQuery($instance)->increment($sortBy);
			$instance->$sortBy = 1;
		}
	}

	public function sort($before, $after, $parent)
	{
		$factory = (new self);
		$sortBy = $this->sortBy();
		$from = $this->$sortBy;

		// if the node has a sibling before it, insert after it
		if ($before) {
			$before = $factory->find($before);
			$to = $before->$sortBy + ($from > $before->$sortBy ? 1 : 0);
		}

		// if the node has a sibling after it, insert before it
		if ($after) {
			$after = $factory->find($after);
			$to = $after->$sortBy - ($from < $before->$sortBy ? 1 : 0);
		}

		$this->moveMe($from, $to);
	}

	protected function moveMe($from, $to)
	{
		$factory = (new self);

		$sortBy = $this->sortBy();
		$sortColumn = '`'.$this->getTable().'`.`'.$sortBy.'`';

		$difference = $from - $to;

		if (abs($difference)) {
			$shift = $difference / abs($difference);
			$factory->modifySortingQuery($this)->whereBetween('sorting', [min($from, $to), max($from, $to)])->update([
				$sortBy => DB::raw($sortColumn.'+'.$shift)
			]);

			$this->$sortBy = $to;
			$this->save();
		}
	}
}