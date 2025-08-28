<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\SegmentDTO;

/**
 * Segment service contract.
 *
 * This interface defines the contract for segment-related operations,
 * including creating, updating, deleting, and managing subscriber segments.
 */
interface SegmentsInterface
{
    /**
     * Create a new segment.
     */
    public function create(SegmentDTO $segment): array;

    /**
     * Get a segment by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing segment.
     */
    public function update(string $id, SegmentDTO $segment): array;

    /**
     * Delete a segment.
     */
    public function delete(string $id): bool;

    /**
     * Get all segments with optional filtering.
     */
    public function list(array $filters = []): array;

    /**
     * Get subscribers in a segment.
     */
    public function getSubscribers(string $id, array $filters = []): array;
}
