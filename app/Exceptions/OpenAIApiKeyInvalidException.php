<?php

namespace App\Exceptions;

use Exception;

class OpenAIApiKeyInvalidException extends Exception
{
    protected $message = 'Something went wrong with AI Service';

    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $code, $previous);
    }
}
