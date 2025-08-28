<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\GroupDTO;
use Ihasan\LaravelMailerlite\Exceptions\GroupCreateException;
use Ihasan\LaravelMailerlite\Exceptions\GroupDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\GroupNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\GroupUpdateException;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Groups\GroupService;
use MailerLiteApi\MailerLite;

describe('GroupService', function () {
    beforeEach(function () {
        $this->mockClient = Mockery::mock(MailerLite::class);
        $this->mockGroupsEndpoint = Mockery::mock();
        $this->mockClient->groups = $this->mockGroupsEndpoint;

        $this->mockManager = Mockery::mock(MailerLiteManager::class);
        $this->mockManager->shouldReceive('getClient')
            ->andReturn($this->mockClient);

        $this->service = new GroupService($this->mockManager);
        $this->groupDTO = GroupDTO::create('Test Group');
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('create', function () {
        test('creates group successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'Test Group',
                'description' => null,
                'active_count' => 0,
                'created_at' => '2023-01-01T00:00:00.000000Z'
            ];

            $this->mockGroupsEndpoint->shouldReceive('create')
                ->once()
                ->with($this->groupDTO->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->create($this->groupDTO);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Test Group')
                ->and($result['active_count'])->toBe(0);
        });

        test('throws GroupCreateException when group already exists', function () {
            $exception = new Exception('Group already exists');

            $this->mockGroupsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->groupDTO))
                ->toThrow(GroupCreateException::class, 'Failed to create group');
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockGroupsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->groupDTO))
                ->toThrow(MailerLiteAuthenticationException::class);
        });

        test('throws GroupCreateException on validation error', function () {
            $exception = new Exception('422 Validation failed');

            $this->mockGroupsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->groupDTO))
                ->toThrow(GroupCreateException::class, 'Validation failed');
        });
    });

    describe('get', function () {
        test('gets group successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'Test Group',
                'description' => null,
                'active_count' => 5
            ];

            $this->mockGroupsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->service->get('123');

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Test Group')
                ->and($result['active_count'])->toBe(5);
        });

        test('returns null when group not found', function () {
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andThrow($exception);

            $result = $this->service->get('123');

            expect($result)->toBeNull();
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockGroupsEndpoint->shouldReceive('find')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->get('123'))
                ->toThrow(MailerLiteAuthenticationException::class);
        });
    });

    describe('update', function () {
        test('updates group successfully', function () {
            $updateDTO = new GroupDTO('Updated Group');
            $expectedResponse = [
                'id' => '123',
                'name' => 'Updated Group',
                'description' => null,
                'active_count' => 5
            ];

            $this->mockGroupsEndpoint->shouldReceive('update')
                ->once()
                ->with('123', $updateDTO->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->update('123', $updateDTO);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Updated Group');
        });

        test('throws GroupNotFoundException when group does not exist', function () {
            $updateDTO = new GroupDTO('Updated Group');
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('update')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->update('123', $updateDTO))
                ->toThrow(GroupNotFoundException::class, "Group with ID '123' not found");
        });

        test('throws GroupUpdateException on validation error', function () {
            $updateDTO = new GroupDTO('Updated Group');
            $exception = new Exception('422 Validation failed');

            $this->mockGroupsEndpoint->shouldReceive('update')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->update('123', $updateDTO))
                ->toThrow(GroupUpdateException::class, 'Validation failed');
        });
    });

    describe('delete', function () {
        test('deletes group successfully', function () {
            $this->mockGroupsEndpoint->shouldReceive('delete')
                ->once()
                ->with('123');

            $result = $this->service->delete('123');

            expect($result)->toBeTrue();
        });

        test('throws GroupNotFoundException when group does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('delete')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->delete('123'))
                ->toThrow(GroupNotFoundException::class, "Group with ID '123' not found");
        });

        test('throws GroupDeleteException on other errors', function () {
            $exception = new Exception('Internal server error');

            $this->mockGroupsEndpoint->shouldReceive('delete')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->delete('123'))
                ->toThrow(GroupDeleteException::class, 'Failed to delete group');
        });
    });

    describe('list', function () {
        test('lists groups successfully', function () {
            $expectedResponse = [
                'data' => [
                    ['id' => '123', 'name' => 'Group 1', 'active_count' => 5],
                    ['id' => '124', 'name' => 'Group 2', 'active_count' => 10]
                ],
                'meta' => ['total' => 2],
                'links' => []
            ];

            $this->mockGroupsEndpoint->shouldReceive('get')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->service->list();

            expect($result)->toBeArray()
                ->and($result['data'])->toHaveCount(2)
                ->and($result['data'][0]['name'])->toBe('Group 1')
                ->and($result['meta']['total'])->toBe(2);
        });

        test('lists groups with filters', function () {
            $filters = ['limit' => 10, 'page' => 1];

            $this->mockGroupsEndpoint->shouldReceive('get')
                ->once()
                ->with($filters)
                ->andReturn(['data' => [], 'meta' => [], 'links' => []]);

            $this->service->list($filters);
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockGroupsEndpoint->shouldReceive('get')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->list())
                ->toThrow(MailerLiteAuthenticationException::class);
        });
    });

    describe('getSubscribers', function () {
        test('gets group subscribers successfully', function () {
            $expectedResponse = [
                'data' => [
                    ['id' => '1', 'email' => 'user1@example.com'],
                    ['id' => '2', 'email' => 'user2@example.com']
                ],
                'meta' => ['total' => 2],
                'links' => []
            ];

            $this->mockGroupsEndpoint->shouldReceive('getSubscribers')
                ->once()
                ->with('123', [])
                ->andReturn($expectedResponse);

            $result = $this->service->getSubscribers('123');

            expect($result)->toBeArray()
                ->and($result['data'])->toHaveCount(2)
                ->and($result['data'][0]['email'])->toBe('user1@example.com');
        });

        test('throws GroupNotFoundException when group does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('getSubscribers')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->getSubscribers('123'))
                ->toThrow(GroupNotFoundException::class, "Group with ID '123' not found");
        });
    });

    describe('addSubscribers', function () {
        test('adds subscribers to group successfully', function () {
            $subscriberIds = ['1', '2'];
            $expectedResponse = ['assigned' => 2];

            $this->mockGroupsEndpoint->shouldReceive('assignSubscriber')
                ->once()
                ->with('123', $subscriberIds)
                ->andReturn($expectedResponse);

            $result = $this->service->addSubscribers('123', $subscriberIds);

            expect($result)->toBe($expectedResponse);
        });

        test('throws GroupNotFoundException when group does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('assignSubscriber')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->addSubscribers('123', ['1', '2']))
                ->toThrow(GroupNotFoundException::class, "Group with ID '123' not found");
        });
    });

    describe('removeSubscribers', function () {
        test('removes subscribers from group successfully', function () {
            $subscriberIds = ['1', '2'];

            $this->mockGroupsEndpoint->shouldReceive('unassignSubscriber')
                ->once()
                ->with('123', $subscriberIds);

            $result = $this->service->removeSubscribers('123', $subscriberIds);

            expect($result)->toBeTrue();
        });

        test('throws GroupNotFoundException when group does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockGroupsEndpoint->shouldReceive('unassignSubscriber')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->removeSubscribers('123', ['1', '2']))
                ->toThrow(GroupNotFoundException::class, "Group with ID '123' not found");
        });
    });

    describe('transformGroupResponse', function () {
        test('transforms response correctly with all fields', function () {
            $response = [
                'id' => '123',
                'name' => 'Test Group',
                'description' => 'Test description',
                'active_count' => 100,
                'sent_count' => 50,
                'opens_count' => 25,
                'open_rate' => 50.0,
                'clicks_count' => 10,
                'click_rate' => 20.0,
                'unsubscribed_count' => 5,
                'unconfirmed_count' => 3,
                'bounced_count' => 2,
                'junk_count' => 1,
                'created_at' => '2023-01-01T00:00:00.000000Z',
                'updated_at' => '2023-01-01T12:00:00.000000Z'
            ];

            $this->mockGroupsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Test Group')
                ->and($result['description'])->toBe('Test description')
                ->and($result['active_count'])->toBe(100)
                ->and($result['open_rate'])->toBe(50.0)
                ->and($result['created_at'])->toBe('2023-01-01T00:00:00.000000Z');
        });

        test('transforms response with missing fields to defaults', function () {
            $response = ['id' => '123', 'name' => 'Test Group'];

            $this->mockGroupsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('Test Group')
                ->and($result['description'])->toBeNull()
                ->and($result['active_count'])->toBe(0)
                ->and($result['open_rate'])->toBe(0)
                ->and($result['created_at'])->toBeNull();
        });
    });
});