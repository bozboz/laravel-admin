<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Services\Sorter;
use Input;
use Illuminate\Routing\Controller;

class SortController extends Controller
{
	public function sort()
	{
		$model = Input::get('model');
		$items = Input::get('items');
		$sorter = new Sorter;
		$sorter->sort(new $model, $items);
	}
}
