<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

/**
 * Exception thrown when authentication with MailerLite API fails.
 *
 * This typically occurs when:
 * - API key is missing or invalid
 * - API key has insufficient permissions
 * - API key has been revoked or expired
 */
class MailerLiteAuthenticationException extends MailerLiteException
{
    /**
     * Create a new authentication exception for missing API key.
     *
     * @return static
     */
    public static function missingApiKey(): static
    {
        return new static(
            'MailerLite API key is missing. Please set MAILERLITE_API_KEY in your environment or config.',
            401,
            null,
            ['type' => 'missing_api_key']
        );
    }

    /**
     * Create a new authentication exception for invalid API key.
     *
     * @return static
     */
    public static function invalidApiKey(): static
    {
        return new static(
            'The provided MailerLite API key is invalid or has been revoked.',
            401,
            null,
            ['type' => 'invalid_api_key']
        );
    }

    /**
     * Create a new authentication exception for insufficient permissions.
     *
     * @param string $resource
     * @return static
     */
    public static function insufficientPermissions(string $resource = ''): static
    {
        $message = 'The MailerLite API key does not have sufficient permissions';
        if ($resource) {
            $message .= " to access {$resource}";
        }
        $message .= '.';

        return new static(
            $message,
            403,
            null,
            ['type' => 'insufficient_permissions', 'resource' => $resource]
        );
    }
}