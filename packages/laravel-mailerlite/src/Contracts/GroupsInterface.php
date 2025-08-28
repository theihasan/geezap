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
     *
     * @param GroupDTO $group
     * @return array
     */
    public function create(GroupDTO $group): array;

    /**
     * Get a group by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Get a group by name.
     *
     * @param string $name
     * @return array|null
     */
    public function getByName(string $name): ?array;

    /**
     * Update an existing group.
     *
     * @param string $id
     * @param GroupDTO $group
     * @return array
     */
    public function update(string $id, GroupDTO $group): array;

    /**
     * Delete a group.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all groups with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;

    /**
     * Get subscribers in a group.
     *
     * @param string $id
     * @param array $filters
     * @return array
     */
    public function getSubscribers(string $id, array $filters = []): array;

    /**
     * Assign subscribers to a group.
     *
     * @param string $id
     * @param array $subscriberIds
     * @return array
     */
    public function assignSubscribers(string $id, array $subscriberIds): array;

    /**
     * Unassign subscribers from a group.
     *
     * @param string $id
     * @param array $subscriberIds
     * @return array
     */
    public function unassignSubscribers(string $id, array $subscriberIds): array;
}