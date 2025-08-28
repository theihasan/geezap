<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\GroupDTO;

/**
 * Group service contract.
 *
 * This interface defines the contract for group-related operations,
 * including creating, updating, deleting, and managing subscriber groups.
 */
interface GroupsInterface
{
    /**
     * Create a new group.
     */
    public function create(GroupDTO $group): array;

    /**
     * Get a group by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Get a group by name.
     */
    public function getByName(string $name): ?array;

    /**
     * Update an existing group.
     */
    public function update(string $id, GroupDTO $group): array;

    /**
     * Delete a group.
     */
    public function delete(string $id): bool;

    /**
     * Get all groups with optional filtering.
     */
    public function list(array $filters = []): array;

    /**
     * Get subscribers in a group.
     */
    public function getSubscribers(string $id, array $filters = []): array;

    /**
     * Assign subscribers to a group.
     */
    public function assignSubscribers(string $id, array $subscriberIds): array;

    /**
     * Unassign subscribers from a group.
     */
    public function unassignSubscribers(string $id, array $subscriberIds): array;
}
