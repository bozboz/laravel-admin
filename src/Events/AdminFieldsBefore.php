<?php

namespace Bozboz\Admin\Events;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Base\ModelInterface;

class AdminFieldsBefore
{
    public $decorator;
    public $instance;

    public function __construct(ModelAdminDecorator $decorator, ModelInterface $instance)
    {
        $this->decorator = $decorator;
        $this->instance = $instance;
    }
}
