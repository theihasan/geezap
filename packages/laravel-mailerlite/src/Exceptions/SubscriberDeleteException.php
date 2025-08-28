<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

/**
 * Exception thrown when subscriber deletion fails.
 */
class SubscriberDeleteException extends MailerLiteException
{
    /**
     * Create a new subscriber deletion exception.
     *
     * @param string $identifier
     * @param string $reason
     * @param \Throwable|null $previous
     * @return static
     */
    public static function make(string $identifier, string $reason, ?\Throwable $previous = null): static
    {
        return new static(
            "Failed to delete subscriber {$identifier}: {$reason}",
            422,
            $previous,
            ['type' => 'subscriber_delete_failed', 'identifier' => $identifier, 'reason' => $reason]
        );
    }
}