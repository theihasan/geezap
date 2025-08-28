<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\DTOs;

use InvalidArgumentException;

/**
 * Subscriber Data Transfer Object
 *
 * This class represents subscriber data with validation and normalization.
 * It ensures that subscriber information is properly formatted and valid
 * before being sent to the MailerLite API.
 */
class SubscriberDTO
{
    /**
     * Create a new subscriber DTO.
     *
     * @param string $email Subscriber's email address (required)
     * @param string|null $name Subscriber's name (optional)
     * @param array $fields Custom field values (optional)
     * @param array $groups Group IDs to assign subscriber to (optional)
     * @param string $status Subscriber status: active, unsubscribed, unconfirmed, bounced, junk (default: active)
     * @param bool $resubscribe Whether to resubscribe if already exists (default: false)
     * @param string|null $type Subscriber type: regular, unsubscribed, imported (optional)
     * @param array $segments Segment IDs to assign subscriber to (optional)
     * @param bool $autoresponders Whether to send autoresponders (default: true)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $email,
        public readonly ?string $name = null,
        public readonly array $fields = [],
        public readonly array $groups = [],
        public readonly string $status = 'active',
        public readonly bool $resubscribe = false,
        public readonly ?string $type = null,
        public readonly array $segments = [],
        public readonly bool $autoresponders = true,
    ) {
        $this->validateEmail($email);
        $this->validateStatus($status);
        $this->validateFields($fields);
        $this->validateGroups($groups);
        $this->validateSegments($segments);
        
        if ($type !== null) {
            $this->validateType($type);
        }
    }

    /**
     * Create a subscriber DTO from an array.
     *
     * @param array $data
     * @return static
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): static
    {
        return new static(
            email: $data['email'] ?? throw new InvalidArgumentException('Email is required'),
            name: $data['name'] ?? null,
            fields: $data['fields'] ?? [],
            groups: $data['groups'] ?? [],
            status: $data['status'] ?? 'active',
            resubscribe: $data['resubscribe'] ?? false,
            type: $data['type'] ?? null,
            segments: $data['segments'] ?? [],
            autoresponders: $data['autoresponders'] ?? true,
        );
    }

    /**
     * Create a basic subscriber with email and name.
     *
     * @param string $email
     * @param string|null $name
     * @return static
     * @throws InvalidArgumentException
     */
    public static function create(string $email, ?string $name = null): static
    {
        return new static(email: $email, name: $name);
    }

    /**
     * Create a subscriber and assign to groups.
     *
     * @param string $email
     * @param string|null $name
     * @param array $groups
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithGroups(string $email, ?string $name, array $groups): static
    {
        return new static(email: $email, name: $name, groups: $groups);
    }

    /**
     * Create a subscriber with custom fields.
     *
     * @param string $email
     * @param string|null $name
     * @param array $fields
     * @return static
     * @throws InvalidArgumentException
     */
    public static function createWithFields(string $email, ?string $name, array $fields): static
    {
        return new static(email: $email, name: $name, fields: $fields);
    }

    /**
     * Convert the DTO to an array for API submission.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = ['email' => $this->email];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if (!empty($this->fields)) {
            $data['fields'] = $this->fields;
        }

        if (!empty($this->groups)) {
            $data['groups'] = $this->groups;
        }

        if ($this->status !== 'active') {
            $data['status'] = $this->status;
        }

        if ($this->resubscribe) {
            $data['resubscribe'] = $this->resubscribe;
        }

        if ($this->type !== null) {
            $data['type'] = $this->type;
        }

        if (!empty($this->segments)) {
            $data['segments'] = $this->segments;
        }

        if (!$this->autoresponders) {
            $data['autoresponders'] = $this->autoresponders;
        }

        return $data;
    }

    /**
     * Get a copy of the DTO with updated fields.
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
     * @param string|null $name
     * @return static
     */
    public function withName(?string $name): static
    {
        return new static(
            email: $this->email,
            name: $name,
            fields: $this->fields,
            groups: $this->groups,
            status: $this->status,
            resubscribe: $this->resubscribe,
            type: $this->type,
            segments: $this->segments,
            autoresponders: $this->autoresponders,
        );
    }

    /**
     * Get a copy with additional groups.
     *
     * @param array $groups
     * @return static
     */
    public function withGroups(array $groups): static
    {
        return new static(
            email: $this->email,
            name: $this->name,
            fields: $this->fields,
            groups: array_unique([...$this->groups, ...$groups]),
            status: $this->status,
            resubscribe: $this->resubscribe,
            type: $this->type,
            segments: $this->segments,
            autoresponders: $this->autoresponders,
        );
    }

    /**
     * Get a copy with additional fields.
     *
     * @param array $fields
     * @return static
     */
    public function withFields(array $fields): static
    {
        return new static(
            email: $this->email,
            name: $this->name,
            fields: array_merge($this->fields, $fields),
            groups: $this->groups,
            status: $this->status,
            resubscribe: $this->resubscribe,
            type: $this->type,
            segments: $this->segments,
            autoresponders: $this->autoresponders,
        );
    }

    /**
     * Validate email address.
     *
     * @param string $email
     * @throws InvalidArgumentException
     */
    private function validateEmail(string $email): void
    {
        if (empty(trim($email))) {
            throw new InvalidArgumentException('Email cannot be empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address: {$email}");
        }

        // Check for common disposable email domains (basic list)
        $disposableDomains = ['10minutemail.com', 'tempmail.org', 'guerrillamail.com', 'mailinator.com'];
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        
        if (in_array($domain, $disposableDomains, true)) {
            throw new InvalidArgumentException("Disposable email addresses are not allowed: {$email}");
        }
    }

    /**
     * Validate subscriber status.
     *
     * @param string $status
     * @throws InvalidArgumentException
     */
    private function validateStatus(string $status): void
    {
        $validStatuses = ['active', 'unsubscribed', 'unconfirmed', 'bounced', 'junk'];
        
        if (!in_array($status, $validStatuses, true)) {
            throw new InvalidArgumentException(
                "Invalid status '{$status}'. Valid statuses: " . implode(', ', $validStatuses)
            );
        }
    }

    /**
     * Validate subscriber type.
     *
     * @param string $type
     * @throws InvalidArgumentException
     */
    private function validateType(string $type): void
    {
        $validTypes = ['regular', 'unsubscribed', 'imported'];
        
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException(
                "Invalid type '{$type}'. Valid types: " . implode(', ', $validTypes)
            );
        }
    }

    /**
     * Validate custom fields.
     *
     * @param array $fields
     * @throws InvalidArgumentException
     */
    private function validateFields(array $fields): void
    {
        foreach ($fields as $key => $value) {
            if (!is_string($key) || empty(trim($key))) {
                throw new InvalidArgumentException('Field keys must be non-empty strings.');
            }

            // Value can be string, number, boolean, or null
            if (!is_scalar($value) && $value !== null) {
                throw new InvalidArgumentException(
                    "Field '{$key}' has invalid value type. Must be scalar or null."
                );
            }
        }
    }

    /**
     * Validate groups array.
     *
     * @param array $groups
     * @throws InvalidArgumentException
     */
    private function validateGroups(array $groups): void
    {
        foreach ($groups as $group) {
            if (!is_string($group) && !is_int($group)) {
                throw new InvalidArgumentException('Group IDs must be strings or integers.');
            }

            if (is_string($group) && empty(trim($group))) {
                throw new InvalidArgumentException('Group IDs cannot be empty strings.');
            }
        }
    }

    /**
     * Validate segments array.
     *
     * @param array $segments
     * @throws InvalidArgumentException
     */
    private function validateSegments(array $segments): void
    {
        foreach ($segments as $segment) {
            if (!is_string($segment) && !is_int($segment)) {
                throw new InvalidArgumentException('Segment IDs must be strings or integers.');
            }

            if (is_string($segment) && empty(trim($segment))) {
                throw new InvalidArgumentException('Segment IDs cannot be empty strings.');
            }
        }
    }
}