<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

/**
 * Automation service contract.
 *
 * This interface defines the contract for automation-related operations,
 * including retrieving and managing automations.
 */
interface AutomationsInterface
{
    /**
     * Get an automation by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Get all automations with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;

    /**
     * Get subscribers in an automation.
     *
     * @param string $id
     * @param array $filters
     * @return array
     */
    public function getSubscribers(string $id, array $filters = []): array;

    /**
     * Get automation activity.
     *
     * @param string $id
     * @param array $filters
     * @return array
     */
    public function getActivity(string $id, array $filters = []): array;
}