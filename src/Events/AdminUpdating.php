<?php

namespace Bozboz\Admin\Events;

use Bozboz\Admin\Base\ModelInterface;

class AdminUpdating
{
    public $instance;
    public $input;

    public function __construct(ModelInterface $instance, array $input)
    {
        $this->instance = $instance;
        $this->input = $input;
    }
}
