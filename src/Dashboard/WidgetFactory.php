<?php

namespace Bozboz\Admin\Dashboard\Widgets;

class WidgetFactory
{
    protected $widgets;

    public function register($name, Widget $widget, $location = 'dashboard')
    {
        $this->widgets[$location][$name] = $widget;
    }

    public function __call($method, $args)
    {
        if ( ! array_key_exists($method, $this->widgets)) {
            throw new \InvalidArgumentException(
                'No action with the name "' . $method . '" is registered'
            );
        }

        return call_user_func_array($this->widgets[$method], $args);
    }
}
