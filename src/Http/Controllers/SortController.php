<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Base\Sortable as DeprecatedSortable;
use Bozboz\Admin\Services\Sorter;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class SortController extends Controller
{
	public function sort()
	{
		$model = Input::get('model');
		$factory = new $model;

		if ($factory instanceof DeprecatedSortable) {
			$items = Input::get('items');
			$sorter = new Sorter;
			$sorter->sort($factory, $items);
			return;
		}

		$instance = $factory->find(Input::get('instance'));
		$instance->sort(Input::get('before'), Input::get('after'), Input::get('parent'));
	}
}
