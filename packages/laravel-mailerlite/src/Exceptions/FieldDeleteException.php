<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when field deletion fails.
 *
 * This exception is thrown when the MailerLite API returns an error
 * while attempting to delete a custom field.
 */
class FieldDeleteException extends MailerLiteException
{
    /**
     * Create a new field deletion exception.
     *
     * @param string $fieldIdentifier
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly string $fieldIdentifier,
        string $message = '',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Create a general field deletion exception.
     *
     * @param string $fieldIdentifier
     * @param string $reason
     * @param Throwable|null $previous
     * @return static
     */
    public static function make(string $fieldIdentifier, string $reason, ?Throwable $previous = null): static
    {
        return new static(
            $fieldIdentifier,
            "Failed to delete field '{$fieldIdentifier}': {$reason}",
            $previous
        );
    }
}