<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Exception;

/**
 * Base exception class for all MailerLite-related exceptions.
 *
 * This exception provides the foundation for all custom exceptions in the package,
 * allowing for consistent error handling and context preservation.
 */
class MailerLiteException extends Exception
{
    /**
     * Additional context data for the exception.
     */
    protected array $context = [];

    /**
     * Create a new MailerLite exception instance.
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Exception|null $previous The previous exception for chaining
     * @param array $context Additional context data
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the exception context.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set additional context data.
     *
     * @param array $context
     * @return static
     */
    public function withContext(array $context): static
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }
}