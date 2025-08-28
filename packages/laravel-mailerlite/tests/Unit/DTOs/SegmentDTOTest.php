<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SegmentDTO;

describe('SegmentDTO', function () {
    test('can create segment DTO with required fields only', function () {
        $filters = [['type' => 'field', 'field' => 'active', 'operator' => 'equals', 'value' => true]];
        $dto = new SegmentDTO('Active Users', $filters);

        expect($dto->name)->toBe('Active Users')
            ->and($dto->filters)->toBe($filters)
            ->and($dto->description)->toBeNull()
            ->and($dto->tags)->toBe([])
            ->and($dto->options)->toBe([])
            ->and($dto->active)->toBeTrue();
    });

    test('can create segment DTO with all fields', function () {
        $filters = [['type' => 'field', 'field' => 'active', 'operator' => 'equals', 'value' => true]];
        $dto = new SegmentDTO(
            name: 'Premium Users',
            filters: $filters,
            description: 'Users with premium subscriptions',
            tags: ['premium', 'active'],
            options: ['auto_refresh' => true],
            active: false
        );

        expect($dto->name)->toBe('Premium Users')
            ->and($dto->filters)->toBe($filters)
            ->and($dto->description)->toBe('Users with premium subscriptions')
            ->and($dto->tags)->toBe(['premium', 'active'])
            ->and($dto->options)->toBe(['auto_refresh' => true])
            ->and($dto->active)->toBeFalse();
    });

    test('can create from array', function () {
        $data = [
            'name' => 'Test Segment',
            'filters' => [['type' => 'field', 'field' => 'test', 'operator' => 'equals', 'value' => 'yes']],
            'description' => 'Test description',
            'tags' => ['test'],
            'options' => ['test' => true],
            'active' => false
        ];

        $dto = SegmentDTO::fromArray($data);

        expect($dto->name)->toBe('Test Segment')
            ->and($dto->filters)->toBe($data['filters'])
            ->and($dto->description)->toBe('Test description')
            ->and($dto->tags)->toBe(['test'])
            ->and($dto->options)->toBe(['test' => true])
            ->and($dto->active)->toBeFalse();
    });

    test('throws exception when creating from array without name', function () {
        expect(fn() => SegmentDTO::fromArray(['filters' => [['type' => 'field', 'field' => 'test', 'operator' => 'equals', 'value' => 'yes']]]))
            ->toThrow(InvalidArgumentException::class, 'Name is required');
    });

    test('throws exception when creating from array without filters', function () {
        expect(fn() => SegmentDTO::fromArray(['name' => 'Test']))
            ->toThrow(InvalidArgumentException::class, 'Filters are required');
    });

    test('can create basic segment', function () {
        $filters = [['type' => 'field', 'field' => 'active', 'operator' => 'equals', 'value' => true]];
        $dto = SegmentDTO::create('Active Users', $filters);

        expect($dto->name)->toBe('Active Users')
            ->and($dto->filters)->toBe($filters);
    });

    test('can create email activity segment', function () {
        $dto = SegmentDTO::emailActivity('Recent Openers', 'opened', 'campaign123', 30);

        expect($dto->name)->toBe('Recent Openers')
            ->and($dto->filters[0]['type'])->toBe('email_activity')
            ->and($dto->filters[0]['activity'])->toBe('opened')
            ->and($dto->filters[0]['campaign_id'])->toBe('campaign123')
            ->and($dto->filters[0]['days'])->toBe(30);
    });

    test('can create field segment', function () {
        $dto = SegmentDTO::field('Age Above 18', 'age', 'greater', 18);

        expect($dto->name)->toBe('Age Above 18')
            ->and($dto->filters[0]['type'])->toBe('field')
            ->and($dto->filters[0]['field'])->toBe('age')
            ->and($dto->filters[0]['operator'])->toBe('greater')
            ->and($dto->filters[0]['value'])->toBe(18);
    });

    test('can create group segment', function () {
        $dto = SegmentDTO::group('In VIP Group', 'group123', true);

        expect($dto->name)->toBe('In VIP Group')
            ->and($dto->filters[0]['type'])->toBe('group')
            ->and($dto->filters[0]['group_id'])->toBe('group123')
            ->and($dto->filters[0]['operator'])->toBe('in');
    });

    test('can create date segment', function () {
        $dto = SegmentDTO::date('Recent Subscribers', 'subscribed_at', 'after', '2023-01-01');

        expect($dto->name)->toBe('Recent Subscribers')
            ->and($dto->filters[0]['type'])->toBe('date')
            ->and($dto->filters[0]['field'])->toBe('subscribed_at')
            ->and($dto->filters[0]['operator'])->toBe('after')
            ->and($dto->filters[0]['value'])->toBe('2023-01-01');
    });

    test('validates name is not empty', function () {
        $filters = [['type' => 'field', 'field' => 'test', 'operator' => 'equals', 'value' => 'yes']];
        
        expect(fn() => new SegmentDTO('', $filters))
            ->toThrow(InvalidArgumentException::class, 'Segment name cannot be empty');
    });

    test('validates filters are not empty', function () {
        expect(fn() => new SegmentDTO('Test', []))
            ->toThrow(InvalidArgumentException::class, 'Segment filters cannot be empty');
    });

    test('validates filter structure', function () {
        expect(fn() => new SegmentDTO('Test', ['invalid_filter']))
            ->toThrow(InvalidArgumentException::class, 'Filter at index 0 must be an array');

        expect(fn() => new SegmentDTO('Test', [['no_type' => 'test']]))
            ->toThrow(InvalidArgumentException::class, "Filter at index 0 must have a 'type' field");
    });

    test('validates field filter structure', function () {
        expect(fn() => new SegmentDTO('Test', [['type' => 'field']]))
            ->toThrow(InvalidArgumentException::class, "Field filter at index 0 must have 'field', 'operator', and 'value'");
    });

    test('can create copies with modifications', function () {
        $original = SegmentDTO::create('Original', [['type' => 'field', 'field' => 'test', 'operator' => 'equals', 'value' => 'yes']]);
        
        $withName = $original->withName('Updated');
        expect($withName->name)->toBe('Updated');
        
        $withDescription = $original->withDescription('New description');
        expect($withDescription->description)->toBe('New description');
        
        $activated = $original->deactivate()->activate();
        expect($activated->active)->toBeTrue();
    });
});