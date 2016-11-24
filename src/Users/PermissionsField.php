<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Fields\Field;
use Bozboz\Admin\Permissions\Permission;
use Form;

class PermissionsField extends Field
{
    public function __construct($options, $attributes = [])
    {
        parent::__construct('permission_options', $attributes);
        $this->options = $options;
    }

    public function getInput()
    {
        $html = '<table class="table table-striped clearfix">
            <tr>
                <th>Actions</th>
                <th>
                    <span class="badge field-helptext" data-toggle="popover" title="" data-content="Format params as a comma separated list" data-original-title="">
                        ?<span class="sr-only">: Format params as a comma separated list</span>
                    </span>
                    Params
                </th>
        ';

        foreach($this->options as $option) {
            if ($option == Permission::WILDCARD) {
                $option = '&#42;';
            }
            $name = $this->get('name') . '[' . $option . ']';
            $checkbox = Form::checkbox(
                $name . '[exists]',
                $option,
                null,
                ['class' => 'js-permission-checkbox']
            );
            $params = Form::text(
                $name . '[params]',
                null,
                [
                    'class' => 'form-control js-parmission-params'
                ]
            );

            $html .= '<tr>
                <td><label>' . $checkbox . ' ' . $option . '</label></td>
                <td>' . $params . '</td>
            </tr>';
        }

        return $html . '</table>';
    }
}