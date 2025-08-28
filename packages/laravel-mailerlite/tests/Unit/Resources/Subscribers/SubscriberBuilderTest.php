<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SubscriberDTO;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberService;
use Mockery as m;

/**
 * SubscriberBuilder Test Suite
 *
 * Tests for the fluent SubscriberBuilder class including method chaining,
 * natural language API, and integration with SubscriberService.
 */
describe('SubscriberBuilder', function () {
    beforeEach(function () {
        $this->mockService = m::mock(SubscriberService::class);
        $this->builder = new SubscriberBuilder($this->mockService);
    });

    afterEach(function () {
        m::close();
    });

    describe('Basic Builder Methods', function () {
        it('sets email correctly', function () {
            $builder = $this->builder->email('test@example.com');

            expect($builder)->toBe($this->builder); // Returns self for chaining

            $dto = $builder->toDTO();
            expect($dto->email)->toBe('test@example.com');
        });

        it('sets name correctly with named()', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->named('John Doe')
                ->toDTO();

            expect($dto->name)->toBe('John Doe');
        });

        it('sets name correctly with withName()', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->withName('Jane Doe')
                ->toDTO();

            expect($dto->name)->toBe('Jane Doe');
        });

        it('sets custom fields correctly', function () {
            $fields = ['company' => 'Acme Inc', 'role' => 'Developer'];

            $dto = $this->builder
                ->email('test@example.com')
                ->withFields($fields)
                ->toDTO();

            expect($dto->fields)->toBe($fields);
        });

        it('adds single field correctly', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->withField('company', 'Tech Corp')
                ->withField('role', 'Engineer')
                ->toDTO();

            expect($dto->fields)->toBe([
                'company' => 'Tech Corp',
                'role' => 'Engineer',
            ]);
        });

        it('merges fields correctly', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->withFields(['company' => 'Acme Inc'])
                ->withFields(['role' => 'Developer'])
                ->withField('level', 'Senior')
                ->toDTO();

            expect($dto->fields)->toBe([
                'company' => 'Acme Inc',
                'role' => 'Developer',
                'level' => 'Senior',
            ]);
        });
    });

    describe('Groups and Segments', function () {
        it('adds single group with toGroup()', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toGroup('developers')
                ->toDTO();

            expect($dto->groups)->toBe(['developers']);
        });

        it('adds multiple groups with toGroup() array', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toGroup(['developers', 'newsletter'])
                ->toDTO();

            expect($dto->groups)->toBe(['developers', 'newsletter']);
        });

        it('adds multiple groups with toGroups() alias', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toGroups(['developers', 'newsletter'])
                ->toDTO();

            expect($dto->groups)->toBe(['developers', 'newsletter']);
        });

        it('accumulates groups from multiple calls', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toGroup('developers')
                ->toGroup('newsletter')
                ->toGroup(['premium', 'beta'])
                ->toDTO();

            expect($dto->groups)->toBe(['developers', 'newsletter', 'premium', 'beta']);
        });

        it('removes duplicate groups', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toGroup('developers')
                ->toGroup('newsletter')
                ->toGroup('developers') // duplicate
                ->toDTO();

            expect($dto->groups)->toBe(['developers', 'newsletter']);
        });

        it('handles segments similar to groups', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->toSegment('active-users')
                ->toSegments(['premium', 'engaged'])
                ->toDTO();

            expect($dto->segments)->toBe(['active-users', 'premium', 'engaged']);
        });
    });

    describe('Status Methods', function () {
        it('sets active status', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->active()
                ->toDTO();

            expect($dto->status)->toBe('active');
        });

        it('sets unsubscribed status', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->unsubscribed()
                ->toDTO();

            expect($dto->status)->toBe('unsubscribed');
        });

        it('sets unconfirmed status', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->unconfirmed()
                ->toDTO();

            expect($dto->status)->toBe('unconfirmed');
        });
    });

    describe('Configuration Methods', function () {
        it('enables resubscribe', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->resubscribeIfExists()
                ->toDTO();

            expect($dto->resubscribe)->toBeTrue();
        });

        it('sets imported type', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->imported()
                ->toDTO();

            expect($dto->type)->toBe('imported');
        });

        it('sets regular type', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->regular()
                ->toDTO();

            expect($dto->type)->toBe('regular');
        });

        it('disables autoresponders', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->withoutAutoresponders()
                ->toDTO();

            expect($dto->autoresponders)->toBeFalse();
        });

        it('enables autoresponders explicitly', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->withoutAutoresponders()
                ->withAutoresponders()
                ->toDTO();

            expect($dto->autoresponders)->toBeTrue();
        });
    });

    describe('Action Methods', function () {
        it('calls service create on subscribe()', function () {
            $expectedResponse = ['id' => '123', 'email' => 'test@example.com'];

            $this->mockService
                ->shouldReceive('create')
                ->once()
                ->with(m::on(function ($dto) {
                    return $dto instanceof SubscriberDTO &&
                           $dto->email === 'test@example.com' &&
                           $dto->name === 'Test User';
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->email('test@example.com')
                ->named('Test User')
                ->subscribe();

            expect($result)->toBe($expectedResponse);
        });

        it('calls service create on create() alias', function () {
            $expectedResponse = ['id' => '123', 'email' => 'test@example.com'];

            $this->mockService
                ->shouldReceive('create')
                ->once()
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->email('test@example.com')
                ->create();

            expect($result)->toBe($expectedResponse);
        });

        it('finds and updates existing subscriber', function () {
            $existingSubscriber = ['id' => '123', 'email' => 'test@example.com'];
            $updatedSubscriber = ['id' => '123', 'email' => 'test@example.com', 'name' => 'Updated Name'];

            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->with('test@example.com')
                ->andReturn($existingSubscriber);

            $this->mockService
                ->shouldReceive('update')
                ->once()
                ->with('123', m::type(SubscriberDTO::class))
                ->andReturn($updatedSubscriber);

            $result = $this->builder
                ->email('test@example.com')
                ->named('Updated Name')
                ->update();

            expect($result)->toBe($updatedSubscriber);
        });

        it('returns null when updating non-existent subscriber', function () {
            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->with('nonexistent@example.com')
                ->andReturn(null);

            $result = $this->builder
                ->email('nonexistent@example.com')
                ->update();

            expect($result)->toBeNull();
        });

        it('finds subscriber correctly', function () {
            $subscriber = ['id' => '123', 'email' => 'test@example.com'];

            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->with('test@example.com')
                ->andReturn($subscriber);

            $result = $this->builder
                ->email('test@example.com')
                ->find();

            expect($result)->toBe($subscriber);
        });

        it('unsubscribes existing subscriber', function () {
            $subscriber = ['id' => '123', 'email' => 'test@example.com'];
            $unsubscribed = ['id' => '123', 'status' => 'unsubscribed'];

            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->andReturn($subscriber);

            $this->mockService
                ->shouldReceive('unsubscribe')
                ->once()
                ->with('123')
                ->andReturn($unsubscribed);

            $result = $this->builder
                ->email('test@example.com')
                ->unsubscribe();

            expect($result)->toBe($unsubscribed);
        });

        it('returns null when unsubscribing non-existent subscriber', function () {
            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->andReturn(null);

            $result = $this->builder
                ->email('nonexistent@example.com')
                ->unsubscribe();

            expect($result)->toBeNull();
        });
    });

    describe('Group Management', function () {
        it('adds subscriber to group', function () {
            $subscriber = ['id' => '123', 'email' => 'test@example.com'];
            $updatedSubscriber = ['id' => '123', 'groups' => ['group1']];

            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->andReturn($subscriber);

            $this->mockService
                ->shouldReceive('addToGroup')
                ->once()
                ->with('123', 'group1')
                ->andReturn($updatedSubscriber);

            $result = $this->builder
                ->email('test@example.com')
                ->addToGroup('group1');

            expect($result)->toBe($updatedSubscriber);
        });

        it('removes subscriber from group', function () {
            $subscriber = ['id' => '123', 'email' => 'test@example.com'];

            $this->mockService
                ->shouldReceive('getByEmail')
                ->once()
                ->andReturn($subscriber);

            $this->mockService
                ->shouldReceive('removeFromGroup')
                ->once()
                ->with('123', 'group1')
                ->andReturn(true);

            $result = $this->builder
                ->email('test@example.com')
                ->removeFromGroup('group1');

            expect($result)->toBeTrue();
        });
    });

    describe('List Operations', function () {
        it('gets subscribers list with filters', function () {
            $filters = ['status' => 'active', 'limit' => 10];
            $expectedResult = ['data' => [], 'meta' => []];

            $this->mockService
                ->shouldReceive('list')
                ->once()
                ->with($filters)
                ->andReturn($expectedResult);

            $result = $this->builder->list($filters);

            expect($result)->toBe($expectedResult);
        });

        it('gets all subscribers', function () {
            $expectedResult = ['data' => [], 'meta' => []];

            $this->mockService
                ->shouldReceive('list')
                ->once()
                ->withNoArgs()
                ->andReturn($expectedResult);

            $result = $this->builder->all();

            expect($result)->toBe($expectedResult);
        });
    });

    describe('Builder State Management', function () {
        it('resets builder state correctly', function () {
            $this->builder
                ->email('test@example.com')
                ->named('Test User')
                ->toGroup('developers')
                ->withField('role', 'admin')
                ->imported()
                ->reset();

            expect(fn () => $this->builder->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });

        it('creates fresh builder instance', function () {
            $fresh = $this->builder
                ->email('test@example.com')
                ->named('Test User')
                ->fresh();

            expect($fresh)->not->toBe($this->builder);
            expect($fresh)->toBeInstanceOf(SubscriberBuilder::class);

            expect(fn () => $fresh->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });
    });

    describe('Magic Method Chaining', function () {
        it('handles "and" prefixed methods', function () {
            $dto = $this->builder
                ->email('test@example.com')
                ->andNamed('John Doe')
                ->andToGroup('developers')
                ->andWithField('role', 'admin')
                ->toDTO();

            expect($dto->email)->toBe('test@example.com');
            expect($dto->name)->toBe('John Doe');
            expect($dto->groups)->toBe(['developers']);
            expect($dto->fields)->toBe(['role' => 'admin']);
        });

        it('throws exception for non-existent methods', function () {
            expect(fn () => $this->builder->nonExistentMethod())
                ->toThrow(BadMethodCallException::class);
        });

        it('throws exception for non-existent "and" methods', function () {
            expect(fn () => $this->builder->andNonExistentMethod())
                ->toThrow(BadMethodCallException::class);
        });
    });

    describe('Error Handling', function () {
        it('throws exception when converting to DTO without email', function () {
            expect(fn () => $this->builder->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });

        it('throws exception when subscribing without email', function () {
            expect(fn () => $this->builder->subscribe())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });

        it('throws exception when finding without email', function () {
            expect(fn () => $this->builder->find())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });

        it('throws exception when updating without email', function () {
            expect(fn () => $this->builder->update())
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });
    });

    describe('Natural Language Examples', function () {
        it('reads like English - basic subscription', function () {
            $this->mockService
                ->shouldReceive('create')
                ->once()
                ->andReturn(['id' => '123']);

            $result = $this->builder
                ->email('john@example.com')
                ->named('John Doe')
                ->toGroup('Newsletter')
                ->subscribe();

            expect($result['id'])->toBe('123');
        });

        it('reads like English - complex subscription', function () {
            $this->mockService
                ->shouldReceive('create')
                ->once()
                ->andReturn(['id' => '456']);

            $result = $this->builder
                ->email('jane@company.com')
                ->named('Jane Smith')
                ->withField('company', 'Tech Corp')
                ->withField('role', 'Developer')
                ->toGroups(['Developers', 'Premium'])
                ->imported()
                ->resubscribeIfExists()
                ->subscribe();

            expect($result['id'])->toBe('456');
        });

        it('reads like English - with "and" chaining', function () {
            $this->mockService
                ->shouldReceive('create')
                ->once()
                ->andReturn(['id' => '789']);

            $result = $this->builder
                ->email('bob@example.com')
                ->andNamed('Bob Wilson')
                ->andWithFields(['department' => 'Engineering'])
                ->andToGroup('Staff')
                ->andRegular()
                ->subscribe();

            expect($result['id'])->toBe('789');
        });
    });
});
