<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\GroupDTO;
use Ihasan\LaravelMailerlite\Resources\Groups\GroupBuilder;
use Ihasan\LaravelMailerlite\Resources\Groups\GroupService;

describe('GroupBuilder', function () {
    beforeEach(function () {
        $this->mockService = Mockery::mock(GroupService::class);
        $this->builder = new GroupBuilder($this->mockService);
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('fluent interface', function () {
        test('name method sets name and returns builder', function () {
            $result = $this->builder->name('Test Group');

            expect($result)->toBe($this->builder);
        });

        test('named method is alias for name', function () {
            $result = $this->builder->named('Test Group');

            expect($result)->toBe($this->builder);
        });

        test('withDescription method sets description and returns builder', function () {
            $result = $this->builder->withDescription('Test description');

            expect($result)->toBe($this->builder);
        });

        test('description method is alias for withDescription', function () {
            $result = $this->builder->description('Test description');

            expect($result)->toBe($this->builder);
        });

        test('withTags method adds tags and returns builder', function () {
            $result = $this->builder->withTags(['tag1', 'tag2']);

            expect($result)->toBe($this->builder);
        });

        test('withTags with string adds single tag', function () {
            $result = $this->builder->withTags('tag1');

            expect($result)->toBe($this->builder);
        });

        test('withTag method adds single tag', function () {
            $result = $this->builder->withTag('tag1');

            expect($result)->toBe($this->builder);
        });

        test('tagged method is alias for withTag', function () {
            $result = $this->builder->tagged('tag1');

            expect($result)->toBe($this->builder);
        });

        test('withSettings method adds settings and returns builder', function () {
            $result = $this->builder->withSettings(['key' => 'value']);

            expect($result)->toBe($this->builder);
        });

        test('withSetting method adds single setting', function () {
            $result = $this->builder->withSetting('key', 'value');

            expect($result)->toBe($this->builder);
        });
    });

    describe('method chaining', function () {
        test('can chain multiple methods together', function () {
            $result = $this->builder
                ->name('Test Group')
                ->withDescription('Test description')
                ->withTag('important')
                ->withSetting('key', 'value');

            expect($result)->toBe($this->builder);
        });

        test('multiple calls accumulate values', function () {
            $this->builder
                ->withTag('tag1')
                ->withTag('tag2')
                ->withSetting('key1', 'value1')
                ->withSetting('key2', 'value2');

            $dto = $this->builder->name('Test')->toDTO();

            expect($dto->tags)->toBe(['tag1', 'tag2'])
                ->and($dto->settings)->toBe(['key1' => 'value1', 'key2' => 'value2']);
        });
    });

    describe('create', function () {
        test('creates group with built data', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Test Group'];

            $this->mockService->shouldReceive('create')
                ->once()
                ->with(Mockery::on(function (GroupDTO $dto) {
                    return $dto->name === 'Test Group' &&
                           $dto->description === 'Test description' &&
                           $dto->tags === ['important'];
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->name('Test Group')
                ->withDescription('Test description')
                ->withTag('important')
                ->create();

            expect($result)->toBe($expectedResponse);
        });

        test('throws exception when name is missing', function () {
            expect(fn() => $this->builder->create())
                ->toThrow(InvalidArgumentException::class, 'Name is required to create GroupDTO');
        });
    });

    describe('update', function () {
        test('updates group with built data', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Updated Group'];

            $this->mockService->shouldReceive('update')
                ->once()
                ->with('123', Mockery::on(function (GroupDTO $dto) {
                    return $dto->name === 'Updated Group';
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->name('Updated Group')
                ->update('123');

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('delete', function () {
        test('delegates to service delete method', function () {
            $this->mockService->shouldReceive('delete')
                ->once()
                ->with('123')
                ->andReturn(true);

            $result = $this->builder->delete('123');

            expect($result)->toBeTrue();
        });
    });

    describe('find', function () {
        test('delegates to service get method', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Test Group'];

            $this->mockService->shouldReceive('get')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->find('123');

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('list', function () {
        test('delegates to service list method without filters', function () {
            $expectedResponse = ['data' => [], 'meta' => [], 'links' => []];

            $this->mockService->shouldReceive('list')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->builder->list();

            expect($result)->toBe($expectedResponse);
        });

        test('delegates to service list method with filters', function () {
            $filters = ['limit' => 10];
            $expectedResponse = ['data' => [], 'meta' => [], 'links' => []];

            $this->mockService->shouldReceive('list')
                ->once()
                ->with($filters)
                ->andReturn($expectedResponse);

            $result = $this->builder->list($filters);

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('all', function () {
        test('delegates to service list method with no filters', function () {
            $expectedResponse = ['data' => [], 'meta' => [], 'links' => []];

            $this->mockService->shouldReceive('list')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->builder->all();

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('subscriber methods', function () {
        test('getSubscribers delegates to service', function () {
            $expectedResponse = ['data' => []];

            $this->mockService->shouldReceive('getSubscribers')
                ->once()
                ->with('123', [])
                ->andReturn($expectedResponse);

            $result = $this->builder->getSubscribers('123');

            expect($result)->toBe($expectedResponse);
        });

        test('addSubscribers delegates to service', function () {
            $subscriberIds = ['1', '2'];
            $expectedResponse = ['assigned' => 2];

            $this->mockService->shouldReceive('addSubscribers')
                ->once()
                ->with('123', $subscriberIds)
                ->andReturn($expectedResponse);

            $result = $this->builder->addSubscribers('123', $subscriberIds);

            expect($result)->toBe($expectedResponse);
        });

        test('removeSubscribers delegates to service', function () {
            $subscriberIds = ['1', '2'];

            $this->mockService->shouldReceive('removeSubscribers')
                ->once()
                ->with('123', $subscriberIds)
                ->andReturn(true);

            $result = $this->builder->removeSubscribers('123', $subscriberIds);

            expect($result)->toBeTrue();
        });
    });

    describe('toDTO', function () {
        test('converts builder state to DTO', function () {
            $dto = $this->builder
                ->name('Test Group')
                ->withDescription('Test description')
                ->withTags(['tag1', 'tag2'])
                ->withSetting('key', 'value')
                ->toDTO();

            expect($dto)->toBeInstanceOf(GroupDTO::class)
                ->and($dto->name)->toBe('Test Group')
                ->and($dto->description)->toBe('Test description')
                ->and($dto->tags)->toBe(['tag1', 'tag2'])
                ->and($dto->settings)->toBe(['key' => 'value']);
        });

        test('throws exception when name is missing', function () {
            expect(fn() => $this->builder->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Name is required to create GroupDTO');
        });

        test('removes duplicate tags', function () {
            $dto = $this->builder
                ->name('Test')
                ->withTag('tag1')
                ->withTag('tag1')
                ->withTag('tag2')
                ->toDTO();

            expect($dto->tags)->toBe(['tag1', 'tag2']);
        });
    });

    describe('reset', function () {
        test('resets builder to initial state', function () {
            $this->builder
                ->name('Test')
                ->withDescription('Description')
                ->withTag('tag')
                ->withSetting('key', 'value');

            $result = $this->builder->reset();

            expect($result)->toBe($this->builder);

            expect(fn() => $this->builder->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Name is required');
        });
    });

    describe('fresh', function () {
        test('creates new builder instance', function () {
            $fresh = $this->builder->fresh();

            expect($fresh)->not->toBe($this->builder)
                ->and($fresh)->toBeInstanceOf(GroupBuilder::class);
        });
    });

    describe('magic method handling', function () {
        test('handles "and" prefixed method calls', function () {
            $result = $this->builder
                ->name('Test')
                ->andWithDescription('Description')
                ->andWithTag('tag');

            expect($result)->toBe($this->builder);

            $dto = $this->builder->toDTO();
            expect($dto->description)->toBe('Description')
                ->and($dto->tags)->toBe(['tag']);
        });

        test('throws exception for unknown methods', function () {
            expect(fn() => $this->builder->unknownMethod())
                ->toThrow(BadMethodCallException::class, 'Method unknownMethod does not exist');
        });

        test('throws exception for unknown "and" prefixed methods', function () {
            expect(fn() => $this->builder->andUnknownMethod())
                ->toThrow(BadMethodCallException::class, 'Method andUnknownMethod does not exist');
        });
    });
});