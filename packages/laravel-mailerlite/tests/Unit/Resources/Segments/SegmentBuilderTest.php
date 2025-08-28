<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SegmentDTO;
use Ihasan\LaravelMailerlite\Resources\Segments\SegmentBuilder;
use Ihasan\LaravelMailerlite\Resources\Segments\SegmentService;

describe('SegmentBuilder', function () {
    beforeEach(function () {
        $this->mockService = Mockery::mock(SegmentService::class);
        $this->builder = new SegmentBuilder($this->mockService);
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('fluent interface', function () {
        test('name method sets name and returns builder', function () {
            $result = $this->builder->name('Active Users');

            expect($result)->toBe($this->builder);
        });

        test('filter methods add filters correctly', function () {
            $this->builder
                ->name('Test Segment')
                ->whereField('active', 'equals', true)
                ->whereGroup('group123', true)
                ->whereDate('created_at', 'after', '2023-01-01')
                ->whereEmailActivity('opened', 'campaign123', 30);

            $dto = $this->builder->toDTO();

            expect($dto->filters)->toHaveCount(4)
                ->and($dto->filters[0]['type'])->toBe('field')
                ->and($dto->filters[1]['type'])->toBe('group')
                ->and($dto->filters[2]['type'])->toBe('date')
                ->and($dto->filters[3]['type'])->toBe('email_activity');
        });

        test('convenient methods work correctly', function () {
            $this->builder
                ->name('Engaged Users')
                ->whoOpened('campaign123', 7)
                ->whoClicked('campaign456', 14)
                ->inGroup('group789')
                ->createdAfter('2023-01-01');

            $dto = $this->builder->toDTO();

            expect($dto->filters)->toHaveCount(4)
                ->and($dto->filters[0]['activity'])->toBe('opened')
                ->and($dto->filters[1]['activity'])->toBe('clicked')
                ->and($dto->filters[2]['operator'])->toBe('in')
                ->and($dto->filters[3]['operator'])->toBe('after');
        });
    });

    describe('create', function () {
        test('creates segment with built data', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Active Users'];

            $this->mockService->shouldReceive('create')
                ->once()
                ->with(Mockery::on(function (SegmentDTO $dto) {
                    return $dto->name === 'Active Users' &&
                           count($dto->filters) === 1 &&
                           $dto->filters[0]['type'] === 'field';
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->name('Active Users')
                ->whereField('active', 'equals', true)
                ->create();

            expect($result)->toBe($expectedResponse);
        });

        test('throws exception when name is missing', function () {
            expect(fn() => $this->builder->whereField('test', 'equals', true)->create())
                ->toThrow(InvalidArgumentException::class, 'Name is required to create SegmentDTO');
        });

        test('throws exception when filters are missing', function () {
            expect(fn() => $this->builder->name('Test')->create())
                ->toThrow(InvalidArgumentException::class, 'At least one filter is required to create SegmentDTO');
        });
    });

    describe('delegation methods', function () {
        test('find delegates to service', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Test Segment'];

            $this->mockService->shouldReceive('get')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->find('123');

            expect($result)->toBe($expectedResponse);
        });

        test('list delegates to service', function () {
            $expectedResponse = ['data' => [], 'meta' => []];

            $this->mockService->shouldReceive('list')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->builder->list();

            expect($result)->toBe($expectedResponse);
        });

        test('getSubscribers delegates to service', function () {
            $expectedResponse = ['data' => []];

            $this->mockService->shouldReceive('getSubscribers')
                ->once()
                ->with('123', [])
                ->andReturn($expectedResponse);

            $result = $this->builder->getSubscribers('123');

            expect($result)->toBe($expectedResponse);
        });

        test('refresh delegates to service', function () {
            $expectedResponse = ['id' => '123', 'name' => 'Test Segment'];

            $this->mockService->shouldReceive('refresh')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->refresh('123');

            expect($result)->toBe($expectedResponse);
        });

        test('getStats delegates to service', function () {
            $expectedResponse = ['subscribers_count' => 150];

            $this->mockService->shouldReceive('getStats')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->getStats('123');

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('toDTO', function () {
        test('converts builder state to DTO', function () {
            $dto = $this->builder
                ->name('Active Users')
                ->whereField('active', 'equals', true)
                ->withDescription('Users who are active')
                ->withTag('active')
                ->toDTO();

            expect($dto)->toBeInstanceOf(SegmentDTO::class)
                ->and($dto->name)->toBe('Active Users')
                ->and($dto->filters)->toHaveCount(1)
                ->and($dto->description)->toBe('Users who are active')
                ->and($dto->tags)->toBe(['active']);
        });
    });

    describe('magic method handling', function () {
        test('handles "and" prefixed method calls', function () {
            $this->builder
                ->name('Test')
                ->whereField('active', 'equals', true)
                ->andWhoOpened('campaign123')
                ->andInGroup('group456');

            $dto = $this->builder->toDTO();

            expect($dto->filters)->toHaveCount(3)
                ->and($dto->filters[0]['type'])->toBe('field')
                ->and($dto->filters[1]['type'])->toBe('email_activity')
                ->and($dto->filters[2]['type'])->toBe('group');
        });
    });
});