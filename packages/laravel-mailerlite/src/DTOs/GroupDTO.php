<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\DTOs;

use InvalidArgumentException;

/**
 * Group Data Transfer Object
 *
 * This class represents group data with validation and normalization.
 * It ensures that group information is properly formatted and valid
 * before being sent to the MailerLite API.
 */
class GroupDTO
{
    /**
     * Create a new group DTO.
     *
     * @param string $name Group name (required)
     * @param string|null $description Group description (optional)
     * @param array $tags Tags associated with the group (optional)
     * @param array $settings Group-specific settings (optional)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly array $tags = [],
        public readonly array $settings = [],
    ) {
        $this->validateName($name);
        $this->validateDescription($description);
        $this->validateTags($tags);
        $this->validateSettings($settings);
    }

    /**
     * Create a group DTO from an array.
     *
     * @param array $data
     * @return static
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'] ?? throw new InvalidArgumentException('Name is required'),
            description: $data['description'] ?? null,
            tags: $data['tags'] ?? [],
            settings: $data['settings'] ?? [],
        );
    }

    /**
     * Create a basic group with name only.
     *
     * @param string $name
     * @return static
     * @throws InvalidArgumentException
     */
    public static function create(string $name): static
    {
        return new static(name: $name);
    }

    /**
     * Create a group with name and description.
     *
     * @param string $name
     * @param string $description
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithDescription(string $name, string $description): static
    {
        return new static(name: $name, description: $description);
    }

    /**
     * Create a group with tags.
     *
     * @param string $name
     * @param array $tags
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithTags(string $name, array $tags): static
    {
        return new static(name: $name, tags: $tags);
    }

    /**
     * Convert the DTO to an array for API submission.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = ['name' => $this->name];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if (!empty($this->tags)) {
            $data['tags'] = $this->tags;
        }

        if (!empty($this->settings)) {
            $data['settings'] = $this->settings;
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
            description: $this->description,
            tags: $this->tags,
            settings: $this->settings,
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
            description: $description,
            tags: $this->tags,
            settings: $this->settings,
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
            description: $this->description,
            tags: array_unique([...$this->tags, ...$tags]),
            settings: $this->settings,
        );
    }

    /**
     * Get a copy with additional settings.
     *
     * @param array $settings
     * @return static
     */
    public function withSettings(array $settings): static
    {
        return new static(
            name: $this->name,
            description: $this->description,
            tags: $this->tags,
            settings: array_merge($this->settings, $settings),
        );
    }

    /**
     * Validate group name.
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    private function validateName(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('Group name cannot be empty.');
        }

        if (strlen($name) > 255) {
            throw new InvalidArgumentException('Group name cannot exceed 255 characters.');
        }

        // Check for reserved names or invalid characters
        if (preg_match('/[<>"\'\\/\\\\]/', $name)) {
            throw new InvalidArgumentException('Group name contains invalid characters: < > " \' / \\');
        }
    }

    /**
     * Validate group description.
     *
     * @param string|null $description
     * @throws InvalidArgumentException
     */
    private function validateDescription(?string $description): void
    {
        if ($description !== null) {
            if (strlen($description) > 1000) {
                throw new InvalidArgumentException('Group description cannot exceed 1000 characters.');
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
     * Validate settings array.
     *
     * @param array $settings
     * @throws InvalidArgumentException
     */
    private function validateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            if (!is_string($key) || empty(trim($key))) {
                throw new InvalidArgumentException('Setting keys must be non-empty strings.');
            }

            // Value can be string, number, boolean, array, or null
            if (!is_scalar($value) && !is_array($value) && $value !== null) {
                throw new InvalidArgumentException(
                    "Setting '{$key}' has invalid value type. Must be scalar, array, or null."
                );
            }
        }
    }
}