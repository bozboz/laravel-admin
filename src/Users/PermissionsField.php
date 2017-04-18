<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Fields\Field;
use Bozboz\Admin\Permissions\Permission;
use Bozboz\Permissions\Exceptions\InvalidParameterException;
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

        foreach($this->options as $alias => $rule) {
            if ($alias == Permission::WILDCARD) {
                $alias = '&#42;';
            }
            $name = $this->get('name') . '[' . $alias . ']';
            $checkbox = Form::checkbox(
                $name . '[exists]',
                $alias
            );

            try {
                // Test that the permission can have params
                $test = new $rule($alias);
                $test->validFor(auth()->user(), new \Illuminate\Support\Fluent);
                $params = Form::text(
                    $name . '[params]',
                    null,
                    [
                        'class' => 'form-control'
                    ]
                );
            } catch (InvalidParameterException $e) {
                $params = Form::hidden($name . '[params]');
            }

            $html .= '<tr>
                <td><label>' . $checkbox . ' ' . $alias . '</label></td>
                <td>' . $params . '</td>
            </tr>';
        }

        return $html . '</table>';
    }
}