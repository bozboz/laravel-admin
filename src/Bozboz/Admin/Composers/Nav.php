<?php namespace Bozboz\Admin\Composers;

use Event;
use Auth;
use Bozboz\Admin\Components\Menu;

class Nav
{
	function compose($view)
	{
		$menu = new Menu;
		Event::fire('admin.renderMenu', array($menu));
		$view->with('menu', $menu)->with('user', Auth::user());
	}
}
