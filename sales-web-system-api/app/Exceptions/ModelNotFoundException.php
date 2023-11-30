<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public function __construct(string $message = 'Model not found.')
    {
        parent::__construct($message);
    }
}
