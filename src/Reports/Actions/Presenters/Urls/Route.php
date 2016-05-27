<?php

namespace Bozboz\Admin\Reports\Actions\Presenters\Urls;

class Route implements Contract
{
    private $routeAlias;

    public function __construct($routeAlias)
    {
        $this->routeAlias = $routeAlias;
    }

    public function compile($instance)
    {
        if (is_array($this->routeAlias)) {
            list($routeAlias, $params) = $this->routeAlias;
            $params = is_array($params) ? $params : [$params];
        } else {
            $routeAlias = $this->routeAlias;
            $params = [];
        }

        if ($instance) {
            array_push($params, $instance->id);
        }

        return route($routeAlias, array_filter($params));
    }
}
