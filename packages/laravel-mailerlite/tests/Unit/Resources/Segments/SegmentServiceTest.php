<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SegmentDTO;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentCreateException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\SegmentUpdateException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Segments\SegmentService;
use MailerLiteApi\MailerLite;

describe('SegmentService', function () {
    beforeEach(function () {
        $this->mockClient = Mockery::mock(MailerLite::class);
        $this->mockSegmentsEndpoint = Mockery::mock();
        $this->mockClient->segments = $this->mockSegmentsEndpoint;

        $this->mockManager = Mockery::mock(MailerLiteManager::class);
        $this->mockManager->shouldReceive('getClient')
            ->andReturn($this->mockClient);

        $this->service = new SegmentService($this->mockManager);
        $this->segmentDTO = SegmentDTO::create('Active Users', [['type' => 'field', 'field' => 'active', 'operator' => 'equals', 'value' => true]]);
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('create', function () {
        test('creates segment successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'Active Users',
                'active' => true,
                'subscribers_count' => 150,
                'created_at' => '2023-01-01T00:00:00.000000Z'
            ];

            $this->mockSegmentsEndpoint->shouldReceive('create')
                ->once()
                ->with($this->segmentDTO->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->create($this->segmentDTO);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Active Users')
                ->and($result['subscribers_count'])->toBe(150);
        });

        test('throws SegmentCreateException on validation error', function () {
            $exception = new Exception('422 Invalid filter configuration');

            $this->mockSegmentsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->segmentDTO))
                ->toThrow(SegmentCreateException::class);
        });
    });

    describe('get', function () {
        test('gets segment successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'Active Users',
                'active' => true,
                'subscribers_count' => 150
            ];

            $this->mockSegmentsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->service->get('123');

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Active Users')
                ->and($result['subscribers_count'])->toBe(150);
        });

        test('returns null when segment not found', function () {
            $exception = new Exception('404 Not found');

            $this->mockSegmentsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andThrow($exception);

            $result = $this->service->get('123');

            expect($result)->toBeNull();
        });
    });

    describe('list', function () {
        test('lists segments successfully', function () {
            $expectedResponse = [
                'data' => [
                    ['id' => '123', 'name' => 'Active Users', 'active' => true],
                    ['id' => '124', 'name' => 'Premium Users', 'active' => false]
                ],
                'meta' => ['total' => 2],
                'links' => []
            ];

            $this->mockSegmentsEndpoint->shouldReceive('get')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->service->list();

            expect($result)->toBeArray()
                ->and($result['data'])->toHaveCount(2)
                ->and($result['data'][0]['name'])->toBe('Active Users')
                ->and($result['meta']['total'])->toBe(2);
        });
    });

    describe('getSubscribers', function () {
        test('gets segment subscribers successfully', function () {
            $expectedResponse = [
                'data' => [
                    ['id' => '1', 'email' => 'user1@example.com'],
                    ['id' => '2', 'email' => 'user2@example.com']
                ],
                'meta' => ['total' => 2]
            ];

            $this->mockSegmentsEndpoint->shouldReceive('getSubscribers')
                ->once()
                ->with('123', [])
                ->andReturn($expectedResponse);

            $result = $this->service->getSubscribers('123');

            expect($result)->toBeArray()
                ->and($result['data'])->toHaveCount(2)
                ->and($result['data'][0]['email'])->toBe('user1@example.com');
        });

        test('throws SegmentNotFoundException when segment does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockSegmentsEndpoint->shouldReceive('getSubscribers')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->getSubscribers('123'))
                ->toThrow(SegmentNotFoundException::class, "Segment with ID '123' not found");
        });
    });

    describe('getStats', function () {
        test('gets segment statistics successfully', function () {
            $expectedResponse = [
                'subscribers_count' => 150,
                'active_count' => 140,
                'unsubscribed_count' => 5,
                'growth_rate' => 12.5,
                'last_calculated_at' => '2023-01-01T12:00:00.000000Z'
            ];

            $this->mockSegmentsEndpoint->shouldReceive('stats')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->service->getStats('123');

            expect($result['subscribers_count'])->toBe(150)
                ->and($result['active_count'])->toBe(140)
                ->and($result['growth_rate'])->toBe(12.5)
                ->and($result['last_calculated_at'])->toBe('2023-01-01T12:00:00.000000Z');
        });
    });

    describe('transformSegmentResponse', function () {
        test('transforms response correctly with all fields', function () {
            $response = [
                'id' => '123',
                'name' => 'Active Users',
                'description' => 'Users who are active',
                'filters' => [['type' => 'field', 'field' => 'active', 'operator' => 'equals', 'value' => true]],
                'active' => true,
                'subscribers_count' => 150,
                'active_count' => 140,
                'last_calculated_at' => '2023-01-01T12:00:00.000000Z',
                'created_at' => '2023-01-01T00:00:00.000000Z'
            ];

            $this->mockSegmentsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Active Users')
                ->and($result['description'])->toBe('Users who are active')
                ->and($result['active'])->toBeTrue()
                ->and($result['subscribers_count'])->toBe(150)
                ->and($result['last_calculated_at'])->toBe('2023-01-01T12:00:00.000000Z');
        });

        test('transforms response with missing fields to defaults', function () {
            $response = ['id' => '123', 'name' => 'Test Segment'];

            $this->mockSegmentsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Test Segment')
                ->and($result['description'])->toBeNull()
                ->and($result['active'])->toBeTrue()
                ->and($result['subscribers_count'])->toBe(0);
        });
    });
});