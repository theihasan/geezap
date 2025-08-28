<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

/**
 * Exception thrown when subscriber creation fails.
 */
class SubscriberCreateException extends MailerLiteException
{
    /**
     * Create a new subscriber creation exception.
     *
     * @param string $email
     * @param string $reason
     * @param \Throwable|null $previous
     * @return static
     */
    public static function make(string $email, string $reason, ?\Throwable $previous = null): static
    {
        return new static(
            "Failed to create subscriber with email {$email}: {$reason}",
            422,
            $previous,
            ['type' => 'subscriber_create_failed', 'email' => $email, 'reason' => $reason]
        );
    }

    /**
     * Create exception for subscriber already exists.
     *
     * @param string $email
     * @return static
     */
    public static function alreadyExists(string $email): static
    {
        return static::make($email, 'Subscriber already exists');
    }

    /**
     * Create exception for invalid subscriber data.
     *
     * @param string $email
     * @param array $errors
     * @return static
     */
    public static function invalidData(string $email, array $errors): static
    {
        $reason = 'Invalid data: ' . implode(', ', $errors);
        return static::make($email, $reason);
    }
}