<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when a segment cannot be found.
 *
 * This exception is thrown when attempting to access a segment that
 * doesn't exist in the MailerLite account.
 */
class SegmentNotFoundException extends MailerLiteException
{
    /**
     * Create a new segment not found exception.
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
     * Create an exception for a segment not found by ID.
     *
     * @param string $segmentId
     * @return static
     */
    public static function withId(string $segmentId): static
    {
        return new static(
            $segmentId,
            "Segment with ID '{$segmentId}' not found"
        );
    }

    /**
     * Create an exception for a segment not found by name.
     *
     * @param string $segmentName
     * @return static
     */
    public static function withName(string $segmentName): static
    {
        return new static(
            $segmentName,
            "Segment with name '{$segmentName}' not found"
        );
    }
}