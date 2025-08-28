<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\CampaignDTO;

/**
 * Campaign service contract.
 *
 * This interface defines the contract for campaign-related operations,
 * including creating, scheduling, sending, and managing campaigns.
 */
interface CampaignsInterface
{
    /**
     * Create a new campaign.
     *
     * @param CampaignDTO $campaign
     * @return array
     */
    public function create(CampaignDTO $campaign): array;

    /**
     * Get a campaign by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing campaign.
     *
     * @param string $id
     * @param CampaignDTO $campaign
     * @return array
     */
    public function update(string $id, CampaignDTO $campaign): array;

    /**
     * Delete a campaign.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all campaigns with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;

    /**
     * Schedule a campaign to be sent at a specific time.
     *
     * @param string $id
     * @param \DateTimeInterface $scheduledAt
     * @return array
     */
    public function schedule(string $id, \DateTimeInterface $scheduledAt): array;

    /**
     * Send a campaign immediately.
     *
     * @param string $id
     * @return array
     */
    public function send(string $id): array;

    /**
     * Cancel a scheduled campaign.
     *
     * @param string $id
     * @return array
     */
    public function cancel(string $id): array;

    /**
     * Get campaign statistics.
     *
     * @param string $id
     * @return array
     */
    public function getStats(string $id): array;

    /**
     * Get campaign subscribers.
     *
     * @param string $id
     * @param array $filters
     * @return array
     */
    public function getSubscribers(string $id, array $filters = []): array;
}