<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\SubscriberDTO;

/**
 * Subscriber service contract.
 *
 * This interface defines the contract for subscriber-related operations,
 * including creating, updating, deleting, and managing subscriber groups.
 */
interface SubscribersInterface
{
    /**
     * Create a new subscriber.
     */
    public function create(SubscriberDTO $subscriber): array;

    /**
     * Get a subscriber by email address.
     */
    public function getByEmail(string $email): ?array;

    /**
     * Get a subscriber by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing subscriber.
     */
    public function update(string $id, SubscriberDTO $subscriber): array;

    /**
     * Delete a subscriber.
     */
    public function delete(string $id): bool;

    /**
     * Get all subscribers with optional filtering.
     */
    public function list(array $filters = []): array;

    /**
     * Add a subscriber to a group.
     */
    public function addToGroup(string $subscriberId, string $groupId): array;

    /**
     * Remove a subscriber from a group.
     */
    public function removeFromGroup(string $subscriberId, string $groupId): bool;

    /**
     * Unsubscribe a subscriber.
     */
    public function unsubscribe(string $id): array;

    /**
     * Resubscribe a subscriber.
     */
    public function resubscribe(string $id): array;
}
