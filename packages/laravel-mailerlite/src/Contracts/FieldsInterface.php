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
     *
     * @param FieldDTO $field
     * @return array
     */
    public function create(FieldDTO $field): array;

    /**
     * Get a field by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Update an existing field.
     *
     * @param string $id
     * @param FieldDTO $field
     * @return array
     */
    public function update(string $id, FieldDTO $field): array;

    /**
     * Delete a field.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get all fields with optional filtering.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array;
}