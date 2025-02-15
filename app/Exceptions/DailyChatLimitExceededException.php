<?php

namespace App\Exceptions;

use Exception;

class DailyChatLimitExceededException extends Exception
{
    public function __construct(string $message = "You have reached your daily chat limit. Try again tomorrow", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
