<?php

namespace Bozboz\Admin\Base;

use Bozboz\Admin\Fields\HiddenField;
use Illuminate\Support\Collection;

abstract class BulkAdminDecorator extends ModelAdminDecorator
{
    /**
     * Return the fields displayed on a bulk create/edit screen
     *
     * @param  $instances
     * @return array
     */
    abstract public function getBulkFields($instances);

    public function buildColumns($instance)
    {
        return array_merge([
            '<input class="js-select-all-instances" type="checkbox">'
                => '<input name="instances[]" value="'.$instance->id.'" class="js-select-instance" type="checkbox">',
        ], $this->getColumns($instance));
    }

    public function findInstances($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function buildBulkFields($instances)
    {
        return array_merge(
            array_filter($this->getBulkFields($instances)),
            $instances->map(function($instance, $key) {
                return new HiddenField('instances['.$key.']', $instance->id);
            })->all()
        );
    }
}
