<?php

namespace Bozboz\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BulkAdminController extends ModelAdminController
{
    public function bulkEdit(Request $request)
    {
        $instances = $this->decorator->findInstances($request->get('instances'));

        if ($instances->count() < 1) {
            return back()->withMessage('You must select at least one item to bulk edit.');
        }

        return View::make('admin::bulk-edit', [
            'fields' => $this->decorator->buildBulkFields($instances),
            'modelName' => str_plural($this->decorator->getHeadingForInstance($instances->first())),
            'labels' => $instances->map(function($instance) {
                return $this->decorator->getLabel($instance);
            }),
            'instances' => $instances,
            'action' => $this->getActionName('bulkUpdate'),
            'method' => 'POST',
            'listingUrl' => $this->getListingUrl($instances->first()),
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $instances = $this->decorator->findInstances($request->get('instances'));
        $instances->each(function($order) use($request) {
            $order->fill($request->except('after_save'));
            $order->save();
        });
        return $this->getUpdateResponse($instances->first());
    }
}
