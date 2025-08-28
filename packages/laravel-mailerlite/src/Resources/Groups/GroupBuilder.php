<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Resources\Groups;

use Ihasan\LaravelMailerlite\DTOs\GroupDTO;

/**
 * Group Builder
 *
 * Provides a fluent, plain-English API for building group operations.
 * This class enables method chaining that reads like natural language.
 * 
 * Example usage:
 *   MailerLite::groups()
 *       ->name('Newsletter Subscribers')
 *       ->withDescription('Weekly newsletter recipients')
 *       ->create();
 */
class GroupBuilder
{
    /**
     * Current group name being built
     */
    protected ?string $name = null;

    /**
     * Current group description being built
     */
    protected ?string $description = null;

    /**
     * Current group tags being built
     */
    protected array $tags = [];

    /**
     * Current group settings being built
     */
    protected array $settings = [];

    /**
     * Create a new group builder instance.
     *
     * @param GroupService $service
     */
    public function __construct(
        protected GroupService $service
    ) {}

    /**
     * Set the group name.
     *
     * @param string $name
     * @return static
     */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Alias for name() - more natural in some contexts.
     *
     * @param string $name
     * @return static
     */
    public function named(string $name): static
    {
        return $this->name($name);
    }

    /**
     * Set the group description.
     *
     * @param string $description
     * @return static
     */
    public function withDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Alias for withDescription() - shorter version.
     *
     * @param string $description
     * @return static
     */
    public function description(string $description): static
    {
        return $this->withDescription($description);
    }

    /**
     * Add tags to the group.
     *
     * @param string|array $tags
     * @return static
     */
    public function withTags(string|array $tags): static
    {
        if (is_string($tags)) {
            $this->tags[] = $tags;
        } else {
            $this->tags = array_merge($this->tags, $tags);
        }
        
        return $this;
    }

    /**
     * Add a single tag to the group.
     *
     * @param string $tag
     * @return static
     */
    public function withTag(string $tag): static
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * Alias for withTag() - more natural in some contexts.
     *
     * @param string $tag
     * @return static
     */
    public function tagged(string $tag): static
    {
        return $this->withTag($tag);
    }

    /**
     * Add settings to the group.
     *
     * @param array $settings
     * @return static
     */
    public function withSettings(array $settings): static
    {
        $this->settings = array_merge($this->settings, $settings);
        return $this;
    }

    /**
     * Add a single setting to the group.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function withSetting(string $key, mixed $value): static
    {
        $this->settings[$key] = $value;
        return $this;
    }

    /**
     * Create the group.
     *
     * @return array
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupCreateException
     * @throws \InvalidArgumentException
     */
    public function create(): array
    {
        $dto = $this->toDTO();
        return $this->service->create($dto);
    }

    /**
     * Find group by current name and update.
     * 
     * Note: This method requires the group ID to be known.
     * Use findByName() first to get the group, then update by ID.
     * 
     * @param string $id
     * @return array
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupUpdateException
     */
    public function update(string $id): array
    {
        $dto = $this->toDTO();
        return $this->service->update($id, $dto);
    }

    /**
     * Delete a group by ID.
     *
     * @param string $id
     * @return bool
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupDeleteException
     */
    public function delete(string $id): bool
    {
        return $this->service->delete($id);
    }

    /**
     * Get a group by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function find(string $id): ?array
    {
        return $this->service->get($id);
    }

    /**
     * Get all groups with optional filters.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array
    {
        return $this->service->list($filters);
    }

    /**
     * Get all groups (no filters).
     *
     * @return array
     */
    public function all(): array
    {
        return $this->service->list();
    }

    /**
     * Get subscribers in a group.
     *
     * @param string $groupId
     * @param array $filters
     * @return array
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException
     */
    public function getSubscribers(string $groupId, array $filters = []): array
    {
        return $this->service->getSubscribers($groupId, $filters);
    }

    /**
     * Add subscribers to a group.
     *
     * @param string $groupId
     * @param array $subscriberIds
     * @return array
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException
     */
    public function addSubscribers(string $groupId, array $subscriberIds): array
    {
        return $this->service->addSubscribers($groupId, $subscriberIds);
    }

    /**
     * Remove subscribers from a group.
     *
     * @param string $groupId
     * @param array $subscriberIds
     * @return bool
     * @throws \Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException
     */
    public function removeSubscribers(string $groupId, array $subscriberIds): bool
    {
        return $this->service->removeSubscribers($groupId, $subscriberIds);
    }

    /**
     * Convert current builder state to DTO.
     *
     * @return GroupDTO
     * @throws \InvalidArgumentException
     */
    public function toDTO(): GroupDTO
    {
        if (!$this->name) {
            throw new \InvalidArgumentException('Name is required to create GroupDTO');
        }

        return new GroupDTO(
            name: $this->name,
            description: $this->description,
            tags: array_unique($this->tags),
            settings: $this->settings,
        );
    }

    /**
     * Reset the builder to initial state.
     *
     * @return static
     */
    public function reset(): static
    {
        $this->name = null;
        $this->description = null;
        $this->tags = [];
        $this->settings = [];

        return $this;
    }

    /**
     * Create a new builder instance from this one.
     *
     * @return static
     */
    public function fresh(): static
    {
        return new static($this->service);
    }

    /**
     * Magic method to handle method chaining with "and" for readability.
     * 
     * Examples:
     *   ->name('Newsletter')->andDescription('Weekly updates')
     *   ->withTag('important')->andCreate()
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments): mixed
    {
        // Handle "and" prefixed methods for natural language chaining
        if (str_starts_with($method, 'and')) {
            $actualMethod = lcfirst(substr($method, 3));
            
            if (method_exists($this, $actualMethod)) {
                return $this->$actualMethod(...$arguments);
            }
        }

        throw new \BadMethodCallException("Method {$method} does not exist on " . static::class);
    }
}