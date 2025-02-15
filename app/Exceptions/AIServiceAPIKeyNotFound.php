<?php

namespace App\Exceptions;

use Exception;

class AIServiceAPIKeyNotFound extends Exception
{
    public function __construct(string $message = "API Key not found for AI", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
