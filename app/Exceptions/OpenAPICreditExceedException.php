<?php declare(strict_types=1);

namespace App\Exceptions;

final class OpenAPICreditExceedException extends \Exception
{
    public function __construct( string $message = 'Your Open API credit limit exceed or Your are on rate limit', int $code = 429, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
