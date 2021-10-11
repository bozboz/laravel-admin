<?php

namespace Bozboz\Admin\Events;

use Bozboz\Admin\Base\ModelInterface;

class AdminDeleting
{
    public $instance;

    public function __construct(ModelInterface $instance)
    {
        $this->$instance = $instance;
    }
}
