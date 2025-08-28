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
     */
    public function create(CampaignDTO $campaign): array;

    /**
     * Get a campaign by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing campaign.
     */
    public function update(string $id, CampaignDTO $campaign): array;

    /**
     * Delete a campaign.
     */
    public function delete(string $id): bool;

    /**
     * Get all campaigns with optional filtering.
     */
    public function list(array $filters = []): array;

    /**
     * Schedule a campaign to be sent at a specific time.
     */
    public function schedule(string $id, \DateTimeInterface $scheduledAt): array;

    /**
     * Send a campaign immediately.
     */
    public function send(string $id): array;

    /**
     * Cancel a scheduled campaign.
     */
    public function cancel(string $id): array;

    /**
     * Get campaign statistics.
     */
    public function getStats(string $id): array;

    /**
     * Get campaign subscribers.
     */
    public function getSubscribers(string $id, array $filters = []): array;
}
