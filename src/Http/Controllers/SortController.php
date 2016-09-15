<?php

namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Base\Sortable as DeprecatedSortable;
use Bozboz\Admin\Services\Sorter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class SortController extends Controller
{
	public function sort(Request $request)
	{
		$model = $request->get('model');
		$factory = new $model;

		if ($factory instanceof DeprecatedSortable) {
			$items = $request->get('items');
			$sorter = new Sorter;
			$sorter->sort($factory, $items);
			return;
		}

		DB::beginTransaction();
		$instance = $factory->find($request->get('instance'));
		$instance->sort($request->get('before'), $request->get('after'), $request->get('parent'));
		DB::commit();
	}
}
