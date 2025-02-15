<?php

namespace App\Exceptions;

use Exception;

class NonAuthenticatedUser extends Exception
{
    public function __construct(string $message = "You have to login to generate cover letter", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
