<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\WebhookDTO;

/**
 * Webhook service contract.
 *
 * This interface defines the contract for webhook-related operations,
 * including creating, updating, deleting, and managing webhooks.
 */
interface WebhooksInterface
{
    /**
     * Create a new webhook.
     *
     * @param WebhookDTO $webhook
     * @return array
     */
    public function create(WebhookDTO $webhook): array;

    /**
     * Get a webhook by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing webhook.
     *
     * @param string $id
     * @param WebhookDTO $webhook
     * @return array
     */
    public function update(string $id, WebhookDTO $webhook): array;

    /**
     * Delete a webhook.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all webhooks with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;
}