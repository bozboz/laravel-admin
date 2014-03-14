<?php namespace Bozboz\Admin\Subscribers;

use Bozboz\Admin\Components\Menu;

class UserEventHandler {

    /**
     * Handle user login events.
     */
    public function onRenderMenu(Menu $menu)
    {
        $menu['Users'] = route('admin.users.index');
    }

    public function subscribe($events)
    {
        $events->listen(
            'admin.renderMenu',
            get_class($this) . '@onRenderMenu'
        );
    }

}