<?php namespace Bozboz\Admin\Services;

use Bozboz\Admin\Models\Sortable;

class Sorter
{
	private $sortIterator = 0;
	private $model;

	public function sort(Sortable $model, Array $items)
	{
		$this->model = $model;
		$this->saveList($items, 0);
	}

	private function saveList(array $list, $parent = 0)
	{
		$sortKey = $this->model->sortBy();

		foreach($list as $item) {
			$instance = $this->model->find($item['id']);
			if (array_key_exists('parent_id', $instance->getAttributes())) {
				$instance->parent_id = $parent;
			}
			$instance->$sortKey = $this->sortIterator;
			$instance->save();
			$this->sortIterator++;
			if(isset($item['children'])) {
				$this->saveList($item['children'], $item['id']);
			}
		}
	}
}
