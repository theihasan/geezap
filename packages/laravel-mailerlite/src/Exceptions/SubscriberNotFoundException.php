<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

/**
 * Exception thrown when a subscriber is not found.
 */
class SubscriberNotFoundException extends MailerLiteException
{
    /**
     * Create a new subscriber not found exception.
     */
    public static function make(string $identifier, string $type = 'email'): static
    {
        return new static(
            "Subscriber not found with {$type}: {$identifier}",
            404,
            null,
            ['type' => 'subscriber_not_found', 'identifier' => $identifier, 'search_type' => $type]
        );
    }

    /**
     * Create exception for subscriber not found by email.
     */
    public static function withEmail(string $email): static
    {
        return static::make($email, 'email');
    }

    /**
     * Create exception for subscriber not found by ID.
     */
    public static function withId(string $id): static
    {
        return static::make($id, 'ID');
    }
}
