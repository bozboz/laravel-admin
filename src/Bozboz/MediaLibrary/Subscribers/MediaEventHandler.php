<?php namespace Bozboz\MediaLibrary\Subscribers;

use Bozboz\Admin\Components\Menu;

class MediaEventHandler
{
	public function onRenderMenu(Menu $menu)
	{
		$menu['Media Library'] = route('admin.media.index');
	}

	public function subscribe($events)
	{
		$class = get_class($this);
		$events->listen('admin.renderMenu', $class . '@onRenderMenu');
	}

}
