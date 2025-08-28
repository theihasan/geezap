<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\DTOs\FieldDTO;

/**
 * Field service contract.
 *
 * This interface defines the contract for custom field-related operations,
 * including creating, updating, deleting, and managing subscriber fields.
 */
interface FieldsInterface
{
    /**
     * Create a new custom field.
     */
    public function create(FieldDTO $field): array;

    /**
     * Get a field by ID.
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing field.
     */
    public function update(string $id, FieldDTO $field): array;

    /**
     * Delete a field.
     */
    public function delete(string $id): bool;

    /**
     * Get all fields with optional filtering.
     */
    public function list(array $filters = []): array;
}
