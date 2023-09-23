<?php

namespace App\Exceptions;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $message = 'Entity not found')
    {
        parent::__construct($message);
    }
}
