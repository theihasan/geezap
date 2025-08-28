<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Resources\Subscribers;

use Ihasan\LaravelMailerlite\Contracts\SubscribersInterface;
use Ihasan\LaravelMailerlite\DTOs\SubscriberDTO;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberCreateException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberUpdateException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;

/**
 * Subscriber Service
 *
 * This service handles all subscriber-related operations with the MailerLite API.
 * It implements the SubscribersInterface and provides comprehensive error handling
 * and data transformation.
 */
class SubscriberService implements SubscribersInterface
{
    /**
     * Create a new subscriber service instance.
     *
     * @param MailerLiteManager $manager
     */
    public function __construct(
        protected MailerLiteManager $manager
    ) {}

    /**
     * Create a new subscriber.
     *
     * @param SubscriberDTO $subscriber
     * @return array
     * @throws SubscriberCreateException
     * @throws MailerLiteAuthenticationException
     */
    public function create(SubscriberDTO $subscriber): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->create($subscriber->toArray());

            return $this->transformSubscriberResponse($response);
        } catch (\Exception $e) {
            $this->handleCreateException($subscriber->email, $e);
        }
    }

    /**
     * Get a subscriber by email address.
     *
     * @param string $email
     * @return array|null
     * @throws MailerLiteAuthenticationException
     */
    public function getByEmail(string $email): ?array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->find($email);

            return $response ? $this->transformSubscriberResponse($response) : null;
        } catch (\Exception $e) {
            // If it's a 404, return null instead of throwing
            if ($this->isNotFoundError($e)) {
                return null;
            }

            $this->handleException($e);
        }
    }

    /**
     * Get a subscriber by ID.
     *
     * @param string $id
     * @return array|null
     * @throws MailerLiteAuthenticationException
     */
    public function getById(string $id): ?array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->get($id);

            return $response ? $this->transformSubscriberResponse($response) : null;
        } catch (\Exception $e) {
            // If it's a 404, return null instead of throwing
            if ($this->isNotFoundError($e)) {
                return null;
            }

            $this->handleException($e);
        }
    }

    /**
     * Update an existing subscriber.
     *
     * @param string $id
     * @param SubscriberDTO $subscriber
     * @return array
     * @throws SubscriberNotFoundException
     * @throws SubscriberUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function update(string $id, SubscriberDTO $subscriber): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->update($id, $subscriber->toArray());

            return $this->transformSubscriberResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($id);
            }

            $this->handleUpdateException($id, $e);
        }
    }

    /**
     * Delete a subscriber.
     *
     * @param string $id
     * @return bool
     * @throws SubscriberNotFoundException
     * @throws SubscriberDeleteException
     * @throws MailerLiteAuthenticationException
     */
    public function delete(string $id): bool
    {
        try {
            $client = $this->manager->getClient();
            $client->subscribers->delete($id);

            return true;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($id);
            }

            $this->handleDeleteException($id, $e);
        }
    }

    /**
     * Get all subscribers with optional filtering.
     *
     * @param array $filters
     * @return array
     * @throws MailerLiteAuthenticationException
     */
    public function list(array $filters = []): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->get($filters);

            return [
                'data' => array_map([$this, 'transformSubscriberResponse'], $response['data'] ?? []),
                'meta' => $response['meta'] ?? [],
                'links' => $response['links'] ?? []
            ];
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Add a subscriber to a group.
     *
     * @param string $subscriberId
     * @param string $groupId
     * @return array
     * @throws SubscriberNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function addToGroup(string $subscriberId, string $groupId): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->addToGroup($subscriberId, $groupId);

            return $this->transformSubscriberResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($subscriberId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Remove a subscriber from a group.
     *
     * @param string $subscriberId
     * @param string $groupId
     * @return bool
     * @throws SubscriberNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function removeFromGroup(string $subscriberId, string $groupId): bool
    {
        try {
            $client = $this->manager->getClient();
            $client->subscribers->removeFromGroup($subscriberId, $groupId);

            return true;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($subscriberId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Unsubscribe a subscriber.
     *
     * @param string $id
     * @return array
     * @throws SubscriberNotFoundException
     * @throws SubscriberUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function unsubscribe(string $id): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->update($id, ['status' => 'unsubscribed']);

            return $this->transformSubscriberResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($id);
            }

            $this->handleUpdateException($id, $e);
        }
    }

    /**
     * Resubscribe a subscriber.
     *
     * @param string $id
     * @return array
     * @throws SubscriberNotFoundException
     * @throws SubscriberUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function resubscribe(string $id): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->subscribers->update($id, ['status' => 'active']);

            return $this->transformSubscriberResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SubscriberNotFoundException::withId($id);
            }

            $this->handleUpdateException($id, $e);
        }
    }

    /**
     * Transform subscriber response data.
     *
     * @param array $response
     * @return array
     */
    protected function transformSubscriberResponse(array $response): array
    {
        return [
            'id' => $response['id'] ?? null,
            'email' => $response['email'] ?? null,
            'name' => $response['name'] ?? null,
            'status' => $response['status'] ?? null,
            'subscribed_at' => $response['subscribed_at'] ?? null,
            'unsubscribed_at' => $response['unsubscribed_at'] ?? null,
            'created_at' => $response['created_at'] ?? null,
            'updated_at' => $response['updated_at'] ?? null,
            'fields' => $response['fields'] ?? [],
            'groups' => $response['groups'] ?? [],
            'segments' => $response['segments'] ?? [],
            'opted_in_at' => $response['opted_in_at'] ?? null,
            'optin_ip' => $response['optin_ip'] ?? null,
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
     * Handle subscriber creation exceptions.
     *
     * @param string $email
     * @param \Exception $e
     * @throws SubscriberCreateException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleCreateException(string $email, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        if (str_contains($message, 'already exists') || str_contains($message, 'duplicate')) {
            throw SubscriberCreateException::alreadyExists($email);
        }

        if (str_contains($message, '422') || str_contains($message, 'validation')) {
            throw SubscriberCreateException::invalidData($email, ['Validation failed']);
        }

        throw SubscriberCreateException::make($email, $e->getMessage(), $e);
    }

    /**
     * Handle subscriber update exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws SubscriberUpdateException
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
            throw SubscriberUpdateException::invalidData($identifier, ['Validation failed']);
        }

        throw SubscriberUpdateException::make($identifier, $e->getMessage(), $e);
    }

    /**
     * Handle subscriber deletion exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws SubscriberDeleteException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleDeleteException(string $identifier, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        throw SubscriberDeleteException::make($identifier, $e->getMessage(), $e);
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