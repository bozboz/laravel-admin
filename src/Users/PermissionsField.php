<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Fields\Field;
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
                <th>Actions</th><th>Params</th>
        ';

        foreach($this->options as $option) {
            if ($option != '*') {
                $name = $this->get('name') . '[' . $option . ']';
                $checkbox = Form::checkbox(
                    $name . '[exists]',
                    $option
                );
                $params = Form::text(
                    $name . '[params]',
                    null,
                    [
                        'class' => 'form-control'
                    ]
                );

                $html .= '<tr>
                    <td><label>' . $checkbox . ' ' . $option . '</label></td>
                    <td>' . $params . '</td>
                </tr>';
            }
        }

        return $html . '</table>';
    }
}