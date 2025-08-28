<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\DTOs;

use InvalidArgumentException;

/**
 * Field Data Transfer Object
 *
 * This class represents custom field data with validation and normalization.
 * It ensures that field information is properly formatted and valid
 * before being sent to the MailerLite API.
 */
class FieldDTO
{
    /**
     * Create a new field DTO.
     *
     * @param string $name Field name/key (required)
     * @param string $type Field type: text, number, date, boolean (required)  
     * @param string|null $title Display title for the field (optional)
     * @param mixed $defaultValue Default value for the field (optional)
     * @param array $options Field-specific options/settings (optional)
     * @param bool $required Whether this field is required (default: false)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly ?string $title = null,
        public readonly mixed $defaultValue = null,
        public readonly array $options = [],
        public readonly bool $required = false,
    ) {
        $this->validateName($name);
        $this->validateType($type);
        $this->validateTitle($title);
        $this->validateDefaultValue($defaultValue, $type);
        $this->validateOptions($options);
    }

    /**
     * Create a field DTO from an array.
     *
     * @param array $data
     * @return static
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'] ?? throw new InvalidArgumentException('Name is required'),
            type: $data['type'] ?? throw new InvalidArgumentException('Type is required'),
            title: $data['title'] ?? null,
            defaultValue: $data['default_value'] ?? null,
            options: $data['options'] ?? [],
            required: $data['required'] ?? false,
        );
    }

    /**
     * Create a text field.
     *
     * @param string $name
     * @param string|null $title
     * @param string|null $defaultValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function text(string $name, ?string $title = null, ?string $defaultValue = null): static
    {
        return new static(
            name: $name,
            type: 'text',
            title: $title,
            defaultValue: $defaultValue
        );
    }

    /**
     * Create a number field.
     *
     * @param string $name
     * @param string|null $title
     * @param int|float|null $defaultValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function number(string $name, ?string $title = null, int|float|null $defaultValue = null): static
    {
        return new static(
            name: $name,
            type: 'number',
            title: $title,
            defaultValue: $defaultValue
        );
    }

    /**
     * Create a date field.
     *
     * @param string $name
     * @param string|null $title
     * @param string|null $defaultValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function date(string $name, ?string $title = null, ?string $defaultValue = null): static
    {
        return new static(
            name: $name,
            type: 'date',
            title: $title,
            defaultValue: $defaultValue
        );
    }

    /**
     * Create a boolean field.
     *
     * @param string $name
     * @param string|null $title
     * @param bool|null $defaultValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function boolean(string $name, ?string $title = null, ?bool $defaultValue = null): static
    {
        return new static(
            name: $name,
            type: 'boolean',
            title: $title,
            defaultValue: $defaultValue
        );
    }

    /**
     * Create a dropdown/select field.
     *
     * @param string $name
     * @param array $options
     * @param string|null $title
     * @param string|null $defaultValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function select(string $name, array $options, ?string $title = null, ?string $defaultValue = null): static
    {
        return new static(
            name: $name,
            type: 'text',
            title: $title,
            defaultValue: $defaultValue,
            options: ['type' => 'select', 'values' => $options]
        );
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
            'type' => $this->type,
        ];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }

        if ($this->defaultValue !== null) {
            $data['default_value'] = $this->defaultValue;
        }

        if (!empty($this->options)) {
            $data['options'] = $this->options;
        }

        if ($this->required) {
            $data['required'] = $this->required;
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
            type: $this->type,
            title: $this->title,
            defaultValue: $this->defaultValue,
            options: $this->options,
            required: $this->required,
        );
    }

    /**
     * Get a copy with a different title.
     *
     * @param string|null $title
     * @return static
     */
    public function withTitle(?string $title): static
    {
        return new static(
            name: $this->name,
            type: $this->type,
            title: $title,
            defaultValue: $this->defaultValue,
            options: $this->options,
            required: $this->required,
        );
    }

    /**
     * Get a copy with a different default value.
     *
     * @param mixed $defaultValue
     * @return static
     */
    public function withDefaultValue(mixed $defaultValue): static
    {
        return new static(
            name: $this->name,
            type: $this->type,
            title: $this->title,
            defaultValue: $defaultValue,
            options: $this->options,
            required: $this->required,
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
            type: $this->type,
            title: $this->title,
            defaultValue: $this->defaultValue,
            options: array_merge($this->options, $options),
            required: $this->required,
        );
    }

    /**
     * Get a copy marked as required.
     *
     * @return static
     */
    public function required(): static
    {
        return new static(
            name: $this->name,
            type: $this->type,
            title: $this->title,
            defaultValue: $this->defaultValue,
            options: $this->options,
            required: true,
        );
    }

    /**
     * Get a copy marked as optional.
     *
     * @return static
     */
    public function optional(): static
    {
        return new static(
            name: $this->name,
            type: $this->type,
            title: $this->title,
            defaultValue: $this->defaultValue,
            options: $this->options,
            required: false,
        );
    }

    /**
     * Validate field name.
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    private function validateName(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('Field name cannot be empty.');
        }

        if (strlen($name) > 100) {
            throw new InvalidArgumentException('Field name cannot exceed 100 characters.');
        }

        // Field name should be a valid identifier (letters, numbers, underscores)
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) {
            throw new InvalidArgumentException(
                'Field name must start with a letter and contain only letters, numbers, and underscores.'
            );
        }
    }

    /**
     * Validate field type.
     *
     * @param string $type
     * @throws InvalidArgumentException
     */
    private function validateType(string $type): void
    {
        $validTypes = ['text', 'number', 'date', 'boolean'];
        
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException(
                "Invalid field type '{$type}'. Valid types: " . implode(', ', $validTypes)
            );
        }
    }

    /**
     * Validate field title.
     *
     * @param string|null $title
     * @throws InvalidArgumentException
     */
    private function validateTitle(?string $title): void
    {
        if ($title !== null) {
            if (strlen($title) > 255) {
                throw new InvalidArgumentException('Field title cannot exceed 255 characters.');
            }
        }
    }

    /**
     * Validate default value matches field type.
     *
     * @param mixed $defaultValue
     * @param string $type
     * @throws InvalidArgumentException
     */
    private function validateDefaultValue(mixed $defaultValue, string $type): void
    {
        if ($defaultValue === null) {
            return;
        }

        switch ($type) {
            case 'text':
                if (!is_string($defaultValue)) {
                    throw new InvalidArgumentException('Default value for text field must be a string.');
                }
                break;

            case 'number':
                if (!is_numeric($defaultValue)) {
                    throw new InvalidArgumentException('Default value for number field must be numeric.');
                }
                break;

            case 'boolean':
                if (!is_bool($defaultValue)) {
                    throw new InvalidArgumentException('Default value for boolean field must be a boolean.');
                }
                break;

            case 'date':
                if (!is_string($defaultValue)) {
                    throw new InvalidArgumentException('Default value for date field must be a string.');
                }
                // Basic date format validation (YYYY-MM-DD)
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $defaultValue)) {
                    throw new InvalidArgumentException(
                        'Default value for date field must be in YYYY-MM-DD format.'
                    );
                }
                break;
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