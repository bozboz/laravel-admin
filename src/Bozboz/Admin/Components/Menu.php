<?php namespace Bozboz\Admin\Components;

use Illuminate\Support\Fluent;

class Menu extends Fluent
{
	public function getTopLevelItems()
	{
		$topLevelItems = array();

		foreach($this->getAttributes() as $item => $link) {
			if (is_array($link)) {
				$topLevelItems[$item] = $link;
			} else {
				$topLevelItems['Content'][$item] = $link;
			}
		}

		return $topLevelItems;
	}
}
