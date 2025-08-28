<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Exceptions;

use Throwable;

/**
 * Exception thrown when segment deletion fails.
 *
 * This exception is thrown when the MailerLite API returns an error
 * while attempting to delete a segment.
 */
class SegmentDeleteException extends MailerLiteException
{
    /**
     * Create a new segment deletion exception.
     *
     * @param string $segmentIdentifier
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly string $segmentIdentifier,
        string $message = '',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Create a general segment deletion exception.
     *
     * @param string $segmentIdentifier
     * @param string $reason
     * @param Throwable|null $previous
     * @return static
     */
    public static function make(string $segmentIdentifier, string $reason, ?Throwable $previous = null): static
    {
        return new static(
            $segmentIdentifier,
            "Failed to delete segment '{$segmentIdentifier}': {$reason}",
            $previous
        );
    }
}