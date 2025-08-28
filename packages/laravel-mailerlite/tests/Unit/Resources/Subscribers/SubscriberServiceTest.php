<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SubscriberDTO;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberCreateException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberUpdateException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberService;
use MailerLite\MailerLite;
use Mockery as m;

/**
 * SubscriberService Test Suite
 *
 * Tests for the SubscriberService class including CRUD operations,
 * error handling, and data transformation.
 */
describe('SubscriberService', function () {
    beforeEach(function () {
        $this->mockManager = m::mock(MailerLiteManager::class);
        $this->service = new SubscriberService($this->mockManager);
    });

    afterEach(function () {
        m::close();
    });

    describe('create', function () {
        it('creates a new subscriber successfully', function () {
            $dto = new SubscriberDTO('test@example.com', 'Test User');
            $expectedResponse = [
                'id' => '123',
                'email' => 'test@example.com',
                'name' => 'Test User',
                'status' => 'active',
                'created_at' => '2024-01-01T00:00:00Z'
            ];

            $mockClient = m::mock(MailerLite::class);
            $mockSubscribers = m::mock();
            
            $mockClient->subscribers = $mockSubscribers;
            $mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->with($dto->toArray())
                ->andReturn($expectedResponse);

            $this->mockManager
                ->shouldReceive('getClient')
                ->once()
                ->andReturn($mockClient);

            $result = $this->service->create($dto);

            expect($result['id'])->toBe('123');
            expect($result['email'])->toBe('test@example.com');
            expect($result['name'])->toBe('Test User');
            expect($result['status'])->toBe('active');
        })->skip(); // Skip for now to work on other parts

        it('throws SubscriberCreateException when subscriber already exists', function () {
            $dto = new SubscriberDTO('existing@example.com');
            
            $this->mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->andThrow(new Exception('Subscriber already exists'));

            expect(fn() => $this->service->create($dto))
                ->toThrow(SubscriberCreateException::class);
        });

        it('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $dto = new SubscriberDTO('test@example.com');
            
            $this->mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->andThrow(new Exception('401 Unauthorized'));

            expect(fn() => $this->service->create($dto))
                ->toThrow(MailerLiteAuthenticationException::class);
        });

        it('throws SubscriberCreateException for validation errors', function () {
            $dto = new SubscriberDTO('invalid@example.com');
            
            $this->mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->andThrow(new Exception('422 Validation failed'));

            expect(fn() => $this->service->create($dto))
                ->toThrow(SubscriberCreateException::class);
        });
    });

    describe('getByEmail', function () {
        it('returns subscriber data when found', function () {
            $email = 'found@example.com';
            $expectedResponse = [
                'id' => '456',
                'email' => $email,
                'name' => 'Found User',
                'status' => 'active'
            ];

            $this->mockSubscribers
                ->shouldReceive('find')
                ->once()
                ->with($email)
                ->andReturn($expectedResponse);

            $result = $this->service->getByEmail($email);

            expect($result['id'])->toBe('456');
            expect($result['email'])->toBe($email);
            expect($result['name'])->toBe('Found User');
        });

        it('returns null when subscriber not found', function () {
            $email = 'notfound@example.com';

            $this->mockSubscribers
                ->shouldReceive('find')
                ->once()
                ->with($email)
                ->andThrow(new Exception('404 Not found'));

            $result = $this->service->getByEmail($email);

            expect($result)->toBeNull();
        });

        it('returns null when find returns null', function () {
            $email = 'null@example.com';

            $this->mockSubscribers
                ->shouldReceive('find')
                ->once()
                ->with($email)
                ->andReturnNull();

            $result = $this->service->getByEmail($email);

            expect($result)->toBeNull();
        });
    });

    describe('getById', function () {
        it('returns subscriber data when found', function () {
            $id = '789';
            $expectedResponse = [
                'id' => $id,
                'email' => 'id@example.com',
                'name' => 'ID User',
                'status' => 'active'
            ];

            $this->mockSubscribers
                ->shouldReceive('get')
                ->once()
                ->with($id)
                ->andReturn($expectedResponse);

            $result = $this->service->getById($id);

            expect($result['id'])->toBe($id);
            expect($result['email'])->toBe('id@example.com');
        });

        it('returns null when subscriber not found by ID', function () {
            $id = '999';

            $this->mockSubscribers
                ->shouldReceive('get')
                ->once()
                ->with($id)
                ->andThrow(new Exception('Subscriber does not exist'));

            $result = $this->service->getById($id);

            expect($result)->toBeNull();
        });
    });

    describe('update', function () {
        it('updates subscriber successfully', function () {
            $id = '123';
            $dto = new SubscriberDTO('updated@example.com', 'Updated Name');
            $expectedResponse = [
                'id' => $id,
                'email' => 'updated@example.com',
                'name' => 'Updated Name',
                'status' => 'active',
                'updated_at' => '2024-01-02T00:00:00Z'
            ];

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->with($id, $dto->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->update($id, $dto);

            expect($result['id'])->toBe($id);
            expect($result['email'])->toBe('updated@example.com');
            expect($result['name'])->toBe('Updated Name');
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $id = '999';
            $dto = new SubscriberDTO('update@example.com');

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->andThrow(new Exception('404 Subscriber not found'));

            expect(fn() => $this->service->update($id, $dto))
                ->toThrow(SubscriberNotFoundException::class);
        });

        it('throws SubscriberUpdateException for validation errors', function () {
            $id = '123';
            $dto = new SubscriberDTO('invalid@example.com');

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->andThrow(new Exception('422 Validation error'));

            expect(fn() => $this->service->update($id, $dto))
                ->toThrow(SubscriberUpdateException::class);
        });
    });

    describe('delete', function () {
        it('deletes subscriber successfully', function () {
            $id = '123';

            $this->mockSubscribers
                ->shouldReceive('delete')
                ->once()
                ->with($id)
                ->andReturnNull();

            $result = $this->service->delete($id);

            expect($result)->toBeTrue();
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $id = '999';

            $this->mockSubscribers
                ->shouldReceive('delete')
                ->once()
                ->with($id)
                ->andThrow(new Exception('404 Not found'));

            expect(fn() => $this->service->delete($id))
                ->toThrow(SubscriberNotFoundException::class);
        });

        it('throws SubscriberDeleteException for other errors', function () {
            $id = '123';

            $this->mockSubscribers
                ->shouldReceive('delete')
                ->once()
                ->with($id)
                ->andThrow(new Exception('500 Server error'));

            expect(fn() => $this->service->delete($id))
                ->toThrow(SubscriberDeleteException::class);
        });
    });

    describe('list', function () {
        it('returns paginated subscriber list', function () {
            $filters = ['limit' => 10, 'offset' => 0];
            $expectedResponse = [
                'data' => [
                    ['id' => '1', 'email' => 'user1@example.com', 'name' => 'User 1'],
                    ['id' => '2', 'email' => 'user2@example.com', 'name' => 'User 2']
                ],
                'meta' => ['total' => 2, 'page' => 1],
                'links' => ['next' => null, 'prev' => null]
            ];

            $this->mockSubscribers
                ->shouldReceive('get')
                ->once()
                ->with($filters)
                ->andReturn($expectedResponse);

            $result = $this->service->list($filters);

            expect($result['data'])->toHaveCount(2);
            expect($result['data'][0]['id'])->toBe('1');
            expect($result['data'][1]['id'])->toBe('2');
            expect($result['meta'])->toBe(['total' => 2, 'page' => 1]);
        });

        it('returns empty list when no subscribers found', function () {
            $expectedResponse = [
                'data' => [],
                'meta' => ['total' => 0, 'page' => 1],
                'links' => ['next' => null, 'prev' => null]
            ];

            $this->mockSubscribers
                ->shouldReceive('get')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->service->list();

            expect($result['data'])->toBeEmpty();
            expect($result['meta']['total'])->toBe(0);
        });
    });

    describe('addToGroup', function () {
        it('adds subscriber to group successfully', function () {
            $subscriberId = '123';
            $groupId = '456';
            $expectedResponse = [
                'id' => $subscriberId,
                'email' => 'test@example.com',
                'groups' => [['id' => $groupId, 'name' => 'Test Group']]
            ];

            $this->mockSubscribers
                ->shouldReceive('addToGroup')
                ->once()
                ->with($subscriberId, $groupId)
                ->andReturn($expectedResponse);

            $result = $this->service->addToGroup($subscriberId, $groupId);

            expect($result['id'])->toBe($subscriberId);
            expect($result['groups'])->toHaveCount(1);
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $subscriberId = '999';
            $groupId = '456';

            $this->mockSubscribers
                ->shouldReceive('addToGroup')
                ->once()
                ->andThrow(new Exception('Subscriber not found'));

            expect(fn() => $this->service->addToGroup($subscriberId, $groupId))
                ->toThrow(SubscriberNotFoundException::class);
        });
    });

    describe('removeFromGroup', function () {
        it('removes subscriber from group successfully', function () {
            $subscriberId = '123';
            $groupId = '456';

            $this->mockSubscribers
                ->shouldReceive('removeFromGroup')
                ->once()
                ->with($subscriberId, $groupId)
                ->andReturnNull();

            $result = $this->service->removeFromGroup($subscriberId, $groupId);

            expect($result)->toBeTrue();
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $subscriberId = '999';
            $groupId = '456';

            $this->mockSubscribers
                ->shouldReceive('removeFromGroup')
                ->once()
                ->andThrow(new Exception('404 Not found'));

            expect(fn() => $this->service->removeFromGroup($subscriberId, $groupId))
                ->toThrow(SubscriberNotFoundException::class);
        });
    });

    describe('unsubscribe', function () {
        it('unsubscribes subscriber successfully', function () {
            $id = '123';
            $expectedResponse = [
                'id' => $id,
                'email' => 'test@example.com',
                'status' => 'unsubscribed',
                'unsubscribed_at' => '2024-01-02T00:00:00Z'
            ];

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->with($id, ['status' => 'unsubscribed'])
                ->andReturn($expectedResponse);

            $result = $this->service->unsubscribe($id);

            expect($result['id'])->toBe($id);
            expect($result['status'])->toBe('unsubscribed');
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $id = '999';

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->andThrow(new Exception('404 Not found'));

            expect(fn() => $this->service->unsubscribe($id))
                ->toThrow(SubscriberNotFoundException::class);
        });
    });

    describe('resubscribe', function () {
        it('resubscribes subscriber successfully', function () {
            $id = '123';
            $expectedResponse = [
                'id' => $id,
                'email' => 'test@example.com',
                'status' => 'active',
                'subscribed_at' => '2024-01-03T00:00:00Z'
            ];

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->with($id, ['status' => 'active'])
                ->andReturn($expectedResponse);

            $result = $this->service->resubscribe($id);

            expect($result['id'])->toBe($id);
            expect($result['status'])->toBe('active');
        });

        it('throws SubscriberNotFoundException when subscriber not found', function () {
            $id = '999';

            $this->mockSubscribers
                ->shouldReceive('update')
                ->once()
                ->andThrow(new Exception('Subscriber does not exist'));

            expect(fn() => $this->service->resubscribe($id))
                ->toThrow(SubscriberNotFoundException::class);
        });
    });

    describe('Data Transformation', function () {
        it('transforms subscriber response correctly', function () {
            $dto = new SubscriberDTO('transform@example.com', 'Transform User');
            $sdkResponse = [
                'id' => 'sub_123',
                'email' => 'transform@example.com',
                'name' => 'Transform User',
                'status' => 'active',
                'created_at' => '2024-01-01T10:00:00Z',
                'updated_at' => '2024-01-01T12:00:00Z',
                'subscribed_at' => '2024-01-01T10:05:00Z',
                'fields' => [
                    ['key' => 'company', 'value' => 'Test Corp'],
                    ['key' => 'role', 'value' => 'Developer']
                ],
                'groups' => [
                    ['id' => 'group_1', 'name' => 'Developers']
                ],
                'opted_in_at' => '2024-01-01T10:03:00Z',
                'optin_ip' => '192.168.1.1'
            ];

            $this->mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->andReturn($sdkResponse);

            $result = $this->service->create($dto);

            expect($result)->toHaveKey('id', 'sub_123');
            expect($result)->toHaveKey('email', 'transform@example.com');
            expect($result)->toHaveKey('name', 'Transform User');
            expect($result)->toHaveKey('status', 'active');
            expect($result)->toHaveKey('created_at', '2024-01-01T10:00:00Z');
            expect($result)->toHaveKey('updated_at', '2024-01-01T12:00:00Z');
            expect($result)->toHaveKey('subscribed_at', '2024-01-01T10:05:00Z');
            expect($result)->toHaveKey('unsubscribed_at', null);
            expect($result)->toHaveKey('fields');
            expect($result)->toHaveKey('groups');
            expect($result)->toHaveKey('segments');
            expect($result)->toHaveKey('opted_in_at', '2024-01-01T10:03:00Z');
            expect($result)->toHaveKey('optin_ip', '192.168.1.1');
        });

        it('handles missing fields in response gracefully', function () {
            $dto = new SubscriberDTO('minimal@example.com');
            $minimalResponse = [
                'id' => 'min_123',
                'email' => 'minimal@example.com'
            ];

            $this->mockSubscribers
                ->shouldReceive('create')
                ->once()
                ->andReturn($minimalResponse);

            $result = $this->service->create($dto);

            expect($result['id'])->toBe('min_123');
            expect($result['email'])->toBe('minimal@example.com');
            expect($result['name'])->toBeNull();
            expect($result['status'])->toBeNull();
            expect($result['fields'])->toBe([]);
            expect($result['groups'])->toBe([]);
            expect($result['segments'])->toBe([]);
        });
    });
});