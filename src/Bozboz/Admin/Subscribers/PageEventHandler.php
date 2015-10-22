<?php namespace Bozboz\Admin\Subscribers;

use Bozboz\Admin\Components\Menu;
use Bozboz\Admin\Models\Page;
use Bozboz\Permissions\Facades\Gate;

class PageEventHandler {

    /**
     * Handle user login events.
     */
    public function onRenderMenu(Menu $menu)
    {
        if (Gate::allows('view_anything')) {
            $menu['Pages'] = route('admin.pages.index');
        }
    }

    public function saving($page)
    {
        $sortField = $page->sortBy();
        $highestSortingValue = Page::where('parent_id', $page->parent_id)->max($sortField);
    }

    public function subscribe($events)
    {
        $events->listen(
            'admin.renderMenu',
            get_class($this) . '@onRenderMenu',
            100
        );
    }
}
