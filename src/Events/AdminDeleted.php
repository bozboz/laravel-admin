<?php

namespace Bozboz\Admin\Events;

use Bozboz\Admin\Base\ModelInterface;

class AdminDeleted
{
    public $instance;

    public function __construct(ModelInterface $instance)
    {
        $this->instance = $instance;
    }
}
