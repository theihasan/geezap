<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when a field cannot be found.
 *
 * This exception is thrown when attempting to access a custom field that
 * doesn't exist in the MailerLite account.
 */
class FieldNotFoundException extends MailerLiteException
{
    /**
     * Create a new field not found exception.
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
     * Create an exception for a field not found by ID.
     *
     * @param string $fieldId
     * @return static
     */
    public static function withId(string $fieldId): static
    {
        return new static(
            $fieldId,
            "Field with ID '{$fieldId}' not found"
        );
    }

    /**
     * Create an exception for a field not found by name.
     *
     * @param string $fieldName
     * @return static
     */
    public static function withName(string $fieldName): static
    {
        return new static(
            $fieldName,
            "Field with name '{$fieldName}' not found"
        );
    }
}