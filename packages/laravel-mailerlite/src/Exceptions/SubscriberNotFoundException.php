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
     *
     * @param string $identifier
     * @param string $type
     * @return static
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
     *
     * @param string $email
     * @return static
     */
    public static function withEmail(string $email): static
    {
        return static::make($email, 'email');
    }

    /**
     * Create exception for subscriber not found by ID.
     *
     * @param string $id
     * @return static
     */
    public static function withId(string $id): static
    {
        return static::make($id, 'ID');
    }
}