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
     */
    public function create(WebhookDTO $webhook): array;

    /**
     * Get a webhook by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing webhook.
     */
    public function update(string $id, WebhookDTO $webhook): array;

    /**
     * Delete a webhook.
     */
    public function delete(string $id): bool;

    /**
     * Get all webhooks with optional filtering.
     */
    public function list(array $filters = []): array;
}
