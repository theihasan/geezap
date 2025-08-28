<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Resources\Groups;

use Ihasan\LaravelMailerlite\Contracts\GroupsInterface;
use Ihasan\LaravelMailerlite\DTOs\GroupDTO;
use Ihasan\LaravelMailerlite\Exceptions\GroupCreateException;
use Ihasan\LaravelMailerlite\Exceptions\GroupDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\GroupUpdateException;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;

/**
 * Group Service
 *
 * This service handles all group-related operations with the MailerLite API.
 * It implements the GroupsInterface and provides comprehensive error handling
 * and data transformation.
 */
class GroupService implements GroupsInterface
{
    /**
     * Create a new group service instance.
     *
     * @param MailerLiteManager $manager
     */
    public function __construct(
        protected MailerLiteManager $manager
    ) {}

    /**
     * Create a new group.
     *
     * @param GroupDTO $group
     * @return array
     * @throws GroupCreateException
     * @throws MailerLiteAuthenticationException
     */
    public function create(GroupDTO $group): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->create($group->toArray());

            return $this->transformGroupResponse($response);
        } catch (\Exception $e) {
            $this->handleCreateException($group->name, $e);
        }
    }

    /**
     * Get a group by ID.
     *
     * @param string $id
     * @return array|null
     * @throws MailerLiteAuthenticationException
     */
    public function get(string $id): ?array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->find($id);

            return $response ? $this->transformGroupResponse($response) : null;
        } catch (\Exception $e) {
            // If it's a 404, return null instead of throwing
            if ($this->isNotFoundError($e)) {
                return null;
            }

            $this->handleException($e);
        }
    }

    /**
     * Update an existing group.
     *
     * @param string $id
     * @param GroupDTO $group
     * @return array
     * @throws GroupNotFoundException
     * @throws GroupUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function update(string $id, GroupDTO $group): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->update($id, $group->toArray());

            return $this->transformGroupResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw GroupNotFoundException::withId($id);
            }

            $this->handleUpdateException($id, $e);
        }
    }

    /**
     * Delete a group.
     *
     * @param string $id
     * @return bool
     * @throws GroupNotFoundException
     * @throws GroupDeleteException
     * @throws MailerLiteAuthenticationException
     */
    public function delete(string $id): bool
    {
        try {
            $client = $this->manager->getClient();
            $client->groups->delete($id);

            return true;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw GroupNotFoundException::withId($id);
            }

            $this->handleDeleteException($id, $e);
        }
    }

    /**
     * Get all groups with optional filtering.
     *
     * @param array $filters
     * @return array
     * @throws MailerLiteAuthenticationException
     */
    public function list(array $filters = []): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->get($filters);

            return [
                'data' => array_map([$this, 'transformGroupResponse'], $response['data'] ?? []),
                'meta' => $response['meta'] ?? [],
                'links' => $response['links'] ?? []
            ];
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Get subscribers in a group.
     *
     * @param string $groupId
     * @param array $filters
     * @return array
     * @throws GroupNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function getSubscribers(string $groupId, array $filters = []): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->getSubscribers($groupId, $filters);

            return [
                'data' => $response['data'] ?? [],
                'meta' => $response['meta'] ?? [],
                'links' => $response['links'] ?? []
            ];
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw GroupNotFoundException::withId($groupId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Add subscribers to a group.
     *
     * @param string $groupId
     * @param array $subscriberIds
     * @return array
     * @throws GroupNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function addSubscribers(string $groupId, array $subscriberIds): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->groups->assignSubscriber($groupId, $subscriberIds);

            return $response;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw GroupNotFoundException::withId($groupId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Remove subscribers from a group.
     *
     * @param string $groupId
     * @param array $subscriberIds
     * @return bool
     * @throws GroupNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function removeSubscribers(string $groupId, array $subscriberIds): bool
    {
        try {
            $client = $this->manager->getClient();
            $client->groups->unassignSubscriber($groupId, $subscriberIds);

            return true;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw GroupNotFoundException::withId($groupId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Transform group response data.
     *
     * @param array $response
     * @return array
     */
    protected function transformGroupResponse(array $response): array
    {
        return [
            'id' => $response['id'] ?? null,
            'name' => $response['name'] ?? null,
            'description' => $response['description'] ?? null,
            'active_count' => $response['active_count'] ?? 0,
            'sent_count' => $response['sent_count'] ?? 0,
            'opens_count' => $response['opens_count'] ?? 0,
            'open_rate' => $response['open_rate'] ?? 0,
            'clicks_count' => $response['clicks_count'] ?? 0,
            'click_rate' => $response['click_rate'] ?? 0,
            'unsubscribed_count' => $response['unsubscribed_count'] ?? 0,
            'unconfirmed_count' => $response['unconfirmed_count'] ?? 0,
            'bounced_count' => $response['bounced_count'] ?? 0,
            'junk_count' => $response['junk_count'] ?? 0,
            'created_at' => $response['created_at'] ?? null,
            'updated_at' => $response['updated_at'] ?? null,
        ];
    }

    /**
     * Check if an exception represents a "not found" error.
     *
     * @param \Exception $e
     * @return bool
     */
    protected function isNotFoundError(\Exception $e): bool
    {
        $message = strtolower($e->getMessage());
        
        return str_contains($message, '404') || 
               str_contains($message, 'not found') ||
               str_contains($message, 'does not exist');
    }

    /**
     * Handle group creation exceptions.
     *
     * @param string $name
     * @param \Exception $e
     * @throws GroupCreateException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleCreateException(string $name, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        if (str_contains($message, 'already exists') || str_contains($message, 'duplicate')) {
            throw GroupCreateException::alreadyExists($name);
        }

        if (str_contains($message, '422') || str_contains($message, 'validation')) {
            throw GroupCreateException::invalidData($name, ['Validation failed']);
        }

        throw GroupCreateException::make($name, $e->getMessage(), $e);
    }

    /**
     * Handle group update exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws GroupUpdateException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleUpdateException(string $identifier, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        if (str_contains($message, '422') || str_contains($message, 'validation')) {
            throw GroupUpdateException::invalidData($identifier, ['Validation failed']);
        }

        throw GroupUpdateException::make($identifier, $e->getMessage(), $e);
    }

    /**
     * Handle group deletion exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws GroupDeleteException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleDeleteException(string $identifier, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        throw GroupDeleteException::make($identifier, $e->getMessage(), $e);
    }

    /**
     * Handle general exceptions.
     *
     * @param \Exception $e
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleException(\Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        throw $e;
    }
}