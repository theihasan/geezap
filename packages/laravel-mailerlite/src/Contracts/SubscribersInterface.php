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
     *
     * @param SubscriberDTO $subscriber
     * @return array
     */
    public function create(SubscriberDTO $subscriber): array;

    /**
     * Get a subscriber by email address.
     *
     * @param string $email
     * @return array|null
     */
    public function getByEmail(string $email): ?array;

    /**
     * Get a subscriber by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing subscriber.
     *
     * @param string $id
     * @param SubscriberDTO $subscriber
     * @return array
     */
    public function update(string $id, SubscriberDTO $subscriber): array;

    /**
     * Delete a subscriber.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all subscribers with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;

    /**
     * Add a subscriber to a group.
     *
     * @param string $subscriberId
     * @param string $groupId
     * @return array
     */
    public function addToGroup(string $subscriberId, string $groupId): array;

    /**
     * Remove a subscriber from a group.
     *
     * @param string $subscriberId
     * @param string $groupId
     * @return bool
     */
    public function removeFromGroup(string $subscriberId, string $groupId): bool;

    /**
     * Unsubscribe a subscriber.
     *
     * @param string $id
     * @return array
     */
    public function unsubscribe(string $id): array;

    /**
     * Resubscribe a subscriber.
     *
     * @param string $id
     * @return array
     */
    public function resubscribe(string $id): array;
}