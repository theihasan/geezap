<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

/**
 * Exception thrown when subscriber update fails.
 */
class SubscriberUpdateException extends MailerLiteException
{
    /**
     * Create a new subscriber update exception.
     *
     * @param string $identifier
     * @param string $reason
     * @param \Throwable|null $previous
     * @return static
     */
    public static function make(string $identifier, string $reason, ?\Throwable $previous = null): static
    {
        return new static(
            "Failed to update subscriber {$identifier}: {$reason}",
            422,
            $previous,
            ['type' => 'subscriber_update_failed', 'identifier' => $identifier, 'reason' => $reason]
        );
    }

    /**
     * Create exception for invalid update data.
     *
     * @param string $identifier
     * @param array $errors
     * @return static
     */
    public static function invalidData(string $identifier, array $errors): static
    {
        $reason = 'Invalid data: ' . implode(', ', $errors);
        return static::make($identifier, $reason);
    }
}