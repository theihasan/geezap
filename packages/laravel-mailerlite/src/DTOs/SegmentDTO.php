<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\DTOs;

use InvalidArgumentException;

/**
 * Segment Data Transfer Object
 *
 * This class represents segment data with validation and normalization.
 * It ensures that segment information is properly formatted and valid
 * before being sent to the MailerLite API.
 */
class SegmentDTO
{
    /**
     * Create a new segment DTO.
     *
     * @param string $name Segment name (required)
     * @param array $filters Segment filter conditions (required)
     * @param string|null $description Segment description (optional)
     * @param array $tags Tags associated with the segment (optional)
     * @param array $options Segment-specific options/settings (optional)
     * @param bool $active Whether the segment is active (default: true)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $name,
        public readonly array $filters,
        public readonly ?string $description = null,
        public readonly array $tags = [],
        public readonly array $options = [],
        public readonly bool $active = true,
    ) {
        $this->validateName($name);
        $this->validateFilters($filters);
        $this->validateDescription($description);
        $this->validateTags($tags);
        $this->validateOptions($options);
    }

    /**
     * Create a segment DTO from an array.
     *
     * @param array $data
     * @return static
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'] ?? throw new InvalidArgumentException('Name is required'),
            filters: $data['filters'] ?? throw new InvalidArgumentException('Filters are required'),
            description: $data['description'] ?? null,
            tags: $data['tags'] ?? [],
            options: $data['options'] ?? [],
            active: $data['active'] ?? true,
        );
    }

    /**
     * Create a basic segment with name and filters.
     *
     * @param string $name
     * @param array $filters
     * @return static
     * @throws InvalidArgumentException
     */
    public static function create(string $name, array $filters): static
    {
        return new static(name: $name, filters: $filters);
    }

    /**
     * Create a segment with name, filters, and description.
     *
     * @param string $name
     * @param array $filters
     * @param string $description
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithDescription(string $name, array $filters, string $description): static
    {
        return new static(name: $name, filters: $filters, description: $description);
    }

    /**
     * Create a segment with tags.
     *
     * @param string $name
     * @param array $filters
     * @param array $tags
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithTags(string $name, array $filters, array $tags): static
    {
        return new static(name: $name, filters: $filters, tags: $tags);
    }

    /**
     * Create an email activity segment (opened, clicked, etc.).
     *
     * @param string $name
     * @param string $activity Type: opened, clicked, not_opened, not_clicked
     * @param string|null $campaignId Campaign ID filter (optional)
     * @param int|null $days Days back to check (optional)
     * @return static
     * @throws InvalidArgumentException
     */
    public static function emailActivity(string $name, string $activity, ?string $campaignId = null, ?int $days = null): static
    {
        $filters = [
            'type' => 'email_activity',
            'activity' => $activity
        ];

        if ($campaignId) {
            $filters['campaign_id'] = $campaignId;
        }

        if ($days) {
            $filters['days'] = $days;
        }

        return new static(name: $name, filters: [$filters]);
    }

    /**
     * Create a subscriber field segment.
     *
     * @param string $name
     * @param string $fieldName
     * @param string $operator Operator: equals, not_equals, contains, not_contains, greater, less, etc.
     * @param mixed $value
     * @return static
     * @throws InvalidArgumentException
     */
    public static function field(string $name, string $fieldName, string $operator, mixed $value): static
    {
        $filters = [
            [
                'type' => 'field',
                'field' => $fieldName,
                'operator' => $operator,
                'value' => $value
            ]
        ];

        return new static(name: $name, filters: $filters);
    }

    /**
     * Create a group membership segment.
     *
     * @param string $name
     * @param string $groupId
     * @param bool $isMember True for "in group", false for "not in group"
     * @return static
     * @throws InvalidArgumentException
     */
    public static function group(string $name, string $groupId, bool $isMember = true): static
    {
        $filters = [
            [
                'type' => 'group',
                'group_id' => $groupId,
                'operator' => $isMember ? 'in' : 'not_in'
            ]
        ];

        return new static(name: $name, filters: $filters);
    }

    /**
     * Create a date-based segment.
     *
     * @param string $name
     * @param string $dateField Field: created_at, subscribed_at, updated_at
     * @param string $operator Operator: after, before, between, exactly
     * @param string|array $value Date value or array for between
     * @return static
     * @throws InvalidArgumentException
     */
    public static function date(string $name, string $dateField, string $operator, string|array $value): static
    {
        $filters = [
            [
                'type' => 'date',
                'field' => $dateField,
                'operator' => $operator,
                'value' => $value
            ]
        ];

        return new static(name: $name, filters: $filters);
    }

    /**
     * Convert the DTO to an array for API submission.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'filters' => $this->filters,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if (!empty($this->tags)) {
            $data['tags'] = $this->tags;
        }

        if (!empty($this->options)) {
            $data['options'] = $this->options;
        }

        if (!$this->active) {
            $data['active'] = $this->active;
        }

        return $data;
    }

    /**
     * Get a copy of the DTO with updated values.
     *
     * @param array $updates
     * @return static
     * @throws InvalidArgumentException
     */
    public function with(array $updates): static
    {
        return static::fromArray(array_merge($this->toArray(), $updates));
    }

    /**
     * Get a copy with a different name.
     *
     * @param string $name
     * @return static
     */
    public function withName(string $name): static
    {
        return new static(
            name: $name,
            filters: $this->filters,
            description: $this->description,
            tags: $this->tags,
            options: $this->options,
            active: $this->active,
        );
    }

    /**
     * Get a copy with different filters.
     *
     * @param array $filters
     * @return static
     */
    public function withFilters(array $filters): static
    {
        return new static(
            name: $this->name,
            filters: $filters,
            description: $this->description,
            tags: $this->tags,
            options: $this->options,
            active: $this->active,
        );
    }

    /**
     * Get a copy with additional filters.
     *
     * @param array $filters
     * @return static
     */
    public function addFilters(array $filters): static
    {
        return new static(
            name: $this->name,
            filters: array_merge($this->filters, $filters),
            description: $this->description,
            tags: $this->tags,
            options: $this->options,
            active: $this->active,
        );
    }

    /**
     * Get a copy with a different description.
     *
     * @param string|null $description
     * @return static
     */
    public function withDescription(?string $description): static
    {
        return new static(
            name: $this->name,
            filters: $this->filters,
            description: $description,
            tags: $this->tags,
            options: $this->options,
            active: $this->active,
        );
    }

    /**
     * Get a copy with additional tags.
     *
     * @param array $tags
     * @return static
     */
    public function withTags(array $tags): static
    {
        return new static(
            name: $this->name,
            filters: $this->filters,
            description: $this->description,
            tags: array_unique([...$this->tags, ...$tags]),
            options: $this->options,
            active: $this->active,
        );
    }

    /**
     * Get a copy with additional options.
     *
     * @param array $options
     * @return static
     */
    public function withOptions(array $options): static
    {
        return new static(
            name: $this->name,
            filters: $this->filters,
            description: $this->description,
            tags: $this->tags,
            options: array_merge($this->options, $options),
            active: $this->active,
        );
    }

    /**
     * Get a copy marked as active.
     *
     * @return static
     */
    public function activate(): static
    {
        return new static(
            name: $this->name,
            filters: $this->filters,
            description: $this->description,
            tags: $this->tags,
            options: $this->options,
            active: true,
        );
    }

    /**
     * Get a copy marked as inactive.
     *
     * @return static
     */
    public function deactivate(): static
    {
        return new static(
            name: $this->name,
            filters: $this->filters,
            description: $this->description,
            tags: $this->tags,
            options: $this->options,
            active: false,
        );
    }

    /**
     * Validate segment name.
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    private function validateName(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('Segment name cannot be empty.');
        }

        if (strlen($name) > 255) {
            throw new InvalidArgumentException('Segment name cannot exceed 255 characters.');
        }

        // Check for reserved names or invalid characters
        if (preg_match('/[<>"\'\\/\\\\]/', $name)) {
            throw new InvalidArgumentException('Segment name contains invalid characters: < > " \' / \\');
        }
    }

    /**
     * Validate segment filters.
     *
     * @param array $filters
     * @throws InvalidArgumentException
     */
    private function validateFilters(array $filters): void
    {
        if (empty($filters)) {
            throw new InvalidArgumentException('Segment filters cannot be empty.');
        }

        foreach ($filters as $index => $filter) {
            if (!is_array($filter)) {
                throw new InvalidArgumentException("Filter at index {$index} must be an array.");
            }

            if (!isset($filter['type'])) {
                throw new InvalidArgumentException("Filter at index {$index} must have a 'type' field.");
            }

            $this->validateFilterType($filter, $index);
        }
    }

    /**
     * Validate individual filter type.
     *
     * @param array $filter
     * @param int $index
     * @throws InvalidArgumentException
     */
    private function validateFilterType(array $filter, int $index): void
    {
        $type = $filter['type'];
        $validTypes = ['field', 'group', 'date', 'email_activity', 'survey', 'automation'];

        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException(
                "Invalid filter type '{$type}' at index {$index}. Valid types: " . implode(', ', $validTypes)
            );
        }

        switch ($type) {
            case 'field':
                if (!isset($filter['field']) || !isset($filter['operator']) || !isset($filter['value'])) {
                    throw new InvalidArgumentException("Field filter at index {$index} must have 'field', 'operator', and 'value'.");
                }
                break;

            case 'group':
                if (!isset($filter['group_id']) || !isset($filter['operator'])) {
                    throw new InvalidArgumentException("Group filter at index {$index} must have 'group_id' and 'operator'.");
                }
                break;

            case 'date':
                if (!isset($filter['field']) || !isset($filter['operator']) || !isset($filter['value'])) {
                    throw new InvalidArgumentException("Date filter at index {$index} must have 'field', 'operator', and 'value'.");
                }
                break;

            case 'email_activity':
                if (!isset($filter['activity'])) {
                    throw new InvalidArgumentException("Email activity filter at index {$index} must have 'activity'.");
                }
                break;
        }
    }

    /**
     * Validate segment description.
     *
     * @param string|null $description
     * @throws InvalidArgumentException
     */
    private function validateDescription(?string $description): void
    {
        if ($description !== null) {
            if (strlen($description) > 1000) {
                throw new InvalidArgumentException('Segment description cannot exceed 1000 characters.');
            }
        }
    }

    /**
     * Validate tags array.
     *
     * @param array $tags
     * @throws InvalidArgumentException
     */
    private function validateTags(array $tags): void
    {
        foreach ($tags as $tag) {
            if (!is_string($tag)) {
                throw new InvalidArgumentException('All tags must be strings.');
            }

            if (empty(trim($tag))) {
                throw new InvalidArgumentException('Tags cannot be empty strings.');
            }

            if (strlen($tag) > 100) {
                throw new InvalidArgumentException('Each tag cannot exceed 100 characters.');
            }
        }
    }

    /**
     * Validate options array.
     *
     * @param array $options
     * @throws InvalidArgumentException
     */
    private function validateOptions(array $options): void
    {
        foreach ($options as $key => $value) {
            if (!is_string($key) || empty(trim($key))) {
                throw new InvalidArgumentException('Option keys must be non-empty strings.');
            }

            // Value can be string, number, boolean, array, or null
            if (!is_scalar($value) && !is_array($value) && $value !== null) {
                throw new InvalidArgumentException(
                    "Option '{$key}' has invalid value type. Must be scalar, array, or null."
                );
            }
        }
    }
}