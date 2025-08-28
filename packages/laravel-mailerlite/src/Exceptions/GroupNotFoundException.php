<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when a group cannot be found.
 *
 * This exception is thrown when attempting to access a group that
 * doesn't exist in the MailerLite account.
 */
class GroupNotFoundException extends MailerLiteException
{
    /**
     * Create a new group not found exception.
     *
     * @param string $identifier
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly string $identifier,
        string $message = '',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Create an exception for a group not found by ID.
     *
     * @param string $groupId
     * @return static
     */
    public static function withId(string $groupId): static
    {
        return new static(
            $groupId,
            "Group with ID '{$groupId}' not found"
        );
    }

    /**
     * Create an exception for a group not found by name.
     *
     * @param string $groupName
     * @return static
     */
    public static function withName(string $groupName): static
    {
        return new static(
            $groupName,
            "Group with name '{$groupName}' not found"
        );
    }
}