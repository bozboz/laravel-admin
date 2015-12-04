<?php

namespace Bozboz\Admin\Base\Composers;

use Event;
use Auth;
use Bozboz\Admin\Base\Components\Menu;

class Nav
{
	function __construct(Menu $menu)
	{
		$this->menu = $menu;
	}

	function compose($view)
	{
		$menu = $this->menu;
		Event::fire('admin.renderMenu', array($menu));
		$view->with('menu', $menu)->with('user', Auth::user());
	}
}
