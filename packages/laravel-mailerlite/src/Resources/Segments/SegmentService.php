<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Resources\Segments;

use Ihasan\LaravelMailerlite\Contracts\SegmentsInterface;
use Ihasan\LaravelMailerlite\DTOs\SegmentDTO;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentCreateException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentUpdateException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;

/**
 * Segment Service
 *
 * This service handles all segment-related operations with the MailerLite API.
 * It implements the SegmentsInterface and provides comprehensive error handling
 * and data transformation.
 */
class SegmentService implements SegmentsInterface
{
    /**
     * Create a new segment service instance.
     *
     * @param MailerLiteManager $manager
     */
    public function __construct(
        protected MailerLiteManager $manager
    ) {}

    /**
     * Create a new segment.
     *
     * @param SegmentDTO $segment
     * @return array
     * @throws SegmentCreateException
     * @throws MailerLiteAuthenticationException
     */
    public function create(SegmentDTO $segment): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->create($segment->toArray());

            return $this->transformSegmentResponse($response);
        } catch (\Exception $e) {
            $this->handleCreateException($segment->name, $e);
        }
    }

    /**
     * Get a segment by ID.
     *
     * @param string $id
     * @return array|null
     * @throws MailerLiteAuthenticationException
     */
    public function get(string $id): ?array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->find($id);

            return $response ? $this->transformSegmentResponse($response) : null;
        } catch (\Exception $e) {
            // If it's a 404, return null instead of throwing
            if ($this->isNotFoundError($e)) {
                return null;
            }

            $this->handleException($e);
        }
    }

    /**
     * Update an existing segment.
     *
     * @param string $id
     * @param SegmentDTO $segment
     * @return array
     * @throws SegmentNotFoundException
     * @throws SegmentUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function update(string $id, SegmentDTO $segment): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->update($id, $segment->toArray());

            return $this->transformSegmentResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($id);
            }

            $this->handleUpdateException($id, $e);
        }
    }

    /**
     * Delete a segment.
     *
     * @param string $id
     * @return bool
     * @throws SegmentNotFoundException
     * @throws SegmentDeleteException
     * @throws MailerLiteAuthenticationException
     */
    public function delete(string $id): bool
    {
        try {
            $client = $this->manager->getClient();
            $client->segments->delete($id);

            return true;
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($id);
            }

            $this->handleDeleteException($id, $e);
        }
    }

    /**
     * Get all segments with optional filtering.
     *
     * @param array $filters
     * @return array
     * @throws MailerLiteAuthenticationException
     */
    public function list(array $filters = []): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->get($filters);

            return [
                'data' => array_map([$this, 'transformSegmentResponse'], $response['data'] ?? []),
                'meta' => $response['meta'] ?? [],
                'links' => $response['links'] ?? []
            ];
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Get subscribers in a segment.
     *
     * @param string $segmentId
     * @param array $filters
     * @return array
     * @throws SegmentNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function getSubscribers(string $segmentId, array $filters = []): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->getSubscribers($segmentId, $filters);

            return [
                'data' => $response['data'] ?? [],
                'meta' => $response['meta'] ?? [],
                'links' => $response['links'] ?? []
            ];
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($segmentId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Refresh/recalculate a segment.
     *
     * @param string $segmentId
     * @return array
     * @throws SegmentNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function refresh(string $segmentId): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->refresh($segmentId);

            return $this->transformSegmentResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($segmentId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Get segment statistics.
     *
     * @param string $segmentId
     * @return array
     * @throws SegmentNotFoundException
     * @throws MailerLiteAuthenticationException
     */
    public function getStats(string $segmentId): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->stats($segmentId);

            return [
                'subscribers_count' => $response['subscribers_count'] ?? 0,
                'active_count' => $response['active_count'] ?? 0,
                'unsubscribed_count' => $response['unsubscribed_count'] ?? 0,
                'unconfirmed_count' => $response['unconfirmed_count'] ?? 0,
                'bounced_count' => $response['bounced_count'] ?? 0,
                'junk_count' => $response['junk_count'] ?? 0,
                'growth_rate' => $response['growth_rate'] ?? 0.0,
                'last_calculated_at' => $response['last_calculated_at'] ?? null,
            ];
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($segmentId);
            }

            $this->handleException($e);
        }
    }

    /**
     * Activate a segment.
     *
     * @param string $segmentId
     * @return array
     * @throws SegmentNotFoundException
     * @throws SegmentUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function activate(string $segmentId): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->activate($segmentId);

            return $this->transformSegmentResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($segmentId);
            }

            $this->handleUpdateException($segmentId, $e);
        }
    }

    /**
     * Deactivate a segment.
     *
     * @param string $segmentId
     * @return array
     * @throws SegmentNotFoundException
     * @throws SegmentUpdateException
     * @throws MailerLiteAuthenticationException
     */
    public function deactivate(string $segmentId): array
    {
        try {
            $client = $this->manager->getClient();
            $response = $client->segments->deactivate($segmentId);

            return $this->transformSegmentResponse($response);
        } catch (\Exception $e) {
            if ($this->isNotFoundError($e)) {
                throw SegmentNotFoundException::withId($segmentId);
            }

            $this->handleUpdateException($segmentId, $e);
        }
    }

    /**
     * Transform segment response data.
     *
     * @param array $response
     * @return array
     */
    protected function transformSegmentResponse(array $response): array
    {
        return [
            'id' => $response['id'] ?? null,
            'name' => $response['name'] ?? null,
            'description' => $response['description'] ?? null,
            'filters' => $response['filters'] ?? [],
            'active' => $response['active'] ?? true,
            'subscribers_count' => $response['subscribers_count'] ?? 0,
            'active_count' => $response['active_count'] ?? 0,
            'unsubscribed_count' => $response['unsubscribed_count'] ?? 0,
            'unconfirmed_count' => $response['unconfirmed_count'] ?? 0,
            'bounced_count' => $response['bounced_count'] ?? 0,
            'junk_count' => $response['junk_count'] ?? 0,
            'last_calculated_at' => $response['last_calculated_at'] ?? null,
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
     * Handle segment creation exceptions.
     *
     * @param string $name
     * @param \Exception $e
     * @throws SegmentCreateException
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
            throw SegmentCreateException::alreadyExists($name);
        }

        if (str_contains($message, '422') || str_contains($message, 'validation')) {
            throw SegmentCreateException::invalidData($name, ['Validation failed']);
        }

        if (str_contains($message, 'filter') || str_contains($message, 'invalid filter')) {
            throw SegmentCreateException::invalidFilters($name, ['Invalid filter configuration']);
        }

        throw SegmentCreateException::make($name, $e->getMessage(), $e);
    }

    /**
     * Handle segment update exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws SegmentUpdateException
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
            throw SegmentUpdateException::invalidData($identifier, ['Validation failed']);
        }

        if (str_contains($message, 'filter') || str_contains($message, 'invalid filter')) {
            throw SegmentUpdateException::invalidFilters($identifier, ['Invalid filter configuration']);
        }

        throw SegmentUpdateException::make($identifier, $e->getMessage(), $e);
    }

    /**
     * Handle segment deletion exceptions.
     *
     * @param string $identifier
     * @param \Exception $e
     * @throws SegmentDeleteException
     * @throws MailerLiteAuthenticationException
     * @return never
     */
    protected function handleDeleteException(string $identifier, \Exception $e): never
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, '401') || str_contains($message, 'unauthorized')) {
            throw MailerLiteAuthenticationException::invalidApiKey();
        }

        throw SegmentDeleteException::make($identifier, $e->getMessage(), $e);
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