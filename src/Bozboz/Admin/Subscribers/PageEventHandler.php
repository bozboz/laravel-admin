<?php namespace Bozboz\Admin\Subscribers;

use Bozboz\Admin\Components\Menu;

class PageEventHandler {

    /**
     * Handle user login events.
     */
    public function onRenderMenu(Menu $menu)
    {
        $menu['Pages'] = route('admin.pages.index');
    }

    public function subscribe($events)
    {
        $events->listen(
            'admin.renderMenu',
            get_class($this) . '@onRenderMenu'
        );
    }

}