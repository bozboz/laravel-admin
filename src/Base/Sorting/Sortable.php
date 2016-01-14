<?php

namespace Bozboz\Admin\Base\Sorting;

interface Sortable
{
	public function sortBy();

	public function sort($before, $after, $parent);
}
