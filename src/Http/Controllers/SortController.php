<?php namespace Bozboz\Admin\Http\Controllers;

use Controller, Input;
use Bozboz\Admin\Services\Sorter;

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
