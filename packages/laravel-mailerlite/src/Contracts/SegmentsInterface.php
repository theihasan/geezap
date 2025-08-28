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
     *
     * @param SegmentDTO $segment
     * @return array
     */
    public function create(SegmentDTO $segment): array;

    /**
     * Get a segment by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing segment.
     *
     * @param string $id
     * @param SegmentDTO $segment
     * @return array
     */
    public function update(string $id, SegmentDTO $segment): array;

    /**
     * Delete a segment.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all segments with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;

    /**
     * Get subscribers in a segment.
     *
     * @param string $id
     * @param array $filters
     * @return array
     */
    public function getSubscribers(string $id, array $filters = []): array;
}