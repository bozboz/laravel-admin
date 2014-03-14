<?php namespace Bozboz\Admin\Composers;

use Event;
use Bozboz\Admin\Components\Menu;

class Nav
{
	function compose($view)
	{
		$menu = new Menu;
		Event::fire('admin.renderMenu', array($menu));
		$view->with('menu', $menu);
	}
}