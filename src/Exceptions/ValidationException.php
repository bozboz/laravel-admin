<?php

namespace Bozboz\Admin\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
