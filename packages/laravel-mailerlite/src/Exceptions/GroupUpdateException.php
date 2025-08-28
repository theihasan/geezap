<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when group update fails.
 *
 * This exception is thrown when the MailerLite API returns an error
 * while attempting to update an existing group.
 */
class GroupUpdateException extends MailerLiteException
{
    /**
     * Create a new group update exception.
     *
     * @param string $groupIdentifier
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly string $groupIdentifier,
        string $message = '',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Create an exception for invalid update data.
     *
     * @param string $groupIdentifier
     * @param array $errors
     * @return static
     */
    public static function invalidData(string $groupIdentifier, array $errors): static
    {
        $errorMessages = implode(', ', $errors);
        return new static(
            $groupIdentifier,
            "Failed to update group '{$groupIdentifier}': {$errorMessages}"
        );
    }

    /**
     * Create a general group update exception.
     *
     * @param string $groupIdentifier
     * @param string $reason
     * @param Throwable|null $previous
     * @return static
     */
    public static function make(string $groupIdentifier, string $reason, ?Throwable $previous = null): static
    {
        return new static(
            $groupIdentifier,
            "Failed to update group '{$groupIdentifier}': {$reason}",
            $previous
        );
    }
}