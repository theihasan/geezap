<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\FieldDTO;
use Ihasan\LaravelMailerlite\Resources\Fields\FieldBuilder;
use Ihasan\LaravelMailerlite\Resources\Fields\FieldService;

describe('FieldBuilder', function () {
    beforeEach(function () {
        $this->mockService = Mockery::mock(FieldService::class);
        $this->builder = new FieldBuilder($this->mockService);
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('fluent interface', function () {
        test('name method sets name and returns builder', function () {
            $result = $this->builder->name('company');

            expect($result)->toBe($this->builder);
        });

        test('named method is alias for name', function () {
            $result = $this->builder->named('company');

            expect($result)->toBe($this->builder);
        });

        test('type method sets type and returns builder', function () {
            $result = $this->builder->type('text');

            expect($result)->toBe($this->builder);
        });

        test('field type methods set correct types', function () {
            $this->builder->asText();
            expect($this->builder->name('test')->toDTO()->type)->toBe('text');

            $this->builder->reset()->asNumber();
            expect($this->builder->name('test')->toDTO()->type)->toBe('number');

            $this->builder->reset()->asDate();
            expect($this->builder->name('test')->toDTO()->type)->toBe('date');

            $this->builder->reset()->asBoolean();
            expect($this->builder->name('test')->toDTO()->type)->toBe('boolean');
        });

        test('asSelect method sets text type with select options', function () {
            $options = ['Small', 'Medium', 'Large'];
            $this->builder->name('size')->asSelect($options);
            $dto = $this->builder->toDTO();

            expect($dto->type)->toBe('text')
                ->and($dto->options)->toBe(['type' => 'select', 'values' => $options]);
        });

        test('withTitle method sets title and returns builder', function () {
            $result = $this->builder->withTitle('Company Name');

            expect($result)->toBe($this->builder);
        });

        test('title method is alias for withTitle', function () {
            $result = $this->builder->title('Company Name');

            expect($result)->toBe($this->builder);
        });

        test('withDefault method sets default value', function () {
            $result = $this->builder->withDefault('Unknown');

            expect($result)->toBe($this->builder);
        });

        test('defaultValue method is alias for withDefault', function () {
            $result = $this->builder->defaultValue('Unknown');

            expect($result)->toBe($this->builder);
        });

        test('withOptions method adds options', function () {
            $result = $this->builder->withOptions(['max_length' => 100]);

            expect($result)->toBe($this->builder);
        });

        test('withOption method adds single option', function () {
            $result = $this->builder->withOption('max_length', 100);

            expect($result)->toBe($this->builder);
        });

        test('required and optional methods set required flag', function () {
            $this->builder->name('test')->type('text');
            
            $this->builder->required();
            expect($this->builder->toDTO()->required)->toBeTrue();

            $this->builder->optional();
            expect($this->builder->toDTO()->required)->toBeFalse();
        });
    });

    describe('validation methods', function () {
        test('minLength and maxLength set options', function () {
            $this->builder->name('test')->asText()->minLength(5)->maxLength(50);
            $dto = $this->builder->toDTO();

            expect($dto->options['min_length'])->toBe(5)
                ->and($dto->options['max_length'])->toBe(50);
        });

        test('minValue and maxValue set options', function () {
            $this->builder->name('age')->asNumber()->minValue(0)->maxValue(120);
            $dto = $this->builder->toDTO();

            expect($dto->options['min_value'])->toBe(0)
                ->and($dto->options['max_value'])->toBe(120);
        });

        test('asEmail sets text type with email validation', function () {
            $this->builder->name('email')->asEmail();
            $dto = $this->builder->toDTO();

            expect($dto->type)->toBe('text')
                ->and($dto->options['validation'])->toBe('email');
        });

        test('asPhone sets text type with phone validation', function () {
            $this->builder->name('phone')->asPhone();
            $dto = $this->builder->toDTO();

            expect($dto->type)->toBe('text')
                ->and($dto->options['validation'])->toBe('phone');
        });
    });

    describe('method chaining', function () {
        test('can chain multiple methods together', function () {
            $result = $this->builder
                ->name('company')
                ->asText()
                ->withTitle('Company Name')
                ->withDefault('Unknown')
                ->required();

            expect($result)->toBe($this->builder);
        });

        test('multiple calls accumulate values', function () {
            $this->builder
                ->withOption('key1', 'value1')
                ->withOption('key2', 'value2');

            $dto = $this->builder->name('test')->type('text')->toDTO();

            expect($dto->options)->toBe(['key1' => 'value1', 'key2' => 'value2']);
        });
    });

    describe('create', function () {
        test('creates field with built data', function () {
            $expectedResponse = ['id' => '123', 'name' => 'company'];

            $this->mockService->shouldReceive('create')
                ->once()
                ->with(Mockery::on(function (FieldDTO $dto) {
                    return $dto->name === 'company' &&
                           $dto->type === 'text' &&
                           $dto->title === 'Company Name' &&
                           $dto->required === true;
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->name('company')
                ->asText()
                ->withTitle('Company Name')
                ->required()
                ->create();

            expect($result)->toBe($expectedResponse);
        });

        test('throws exception when name is missing', function () {
            expect(fn() => $this->builder->type('text')->create())
                ->toThrow(InvalidArgumentException::class, 'Name is required to create FieldDTO');
        });

        test('throws exception when type is missing', function () {
            expect(fn() => $this->builder->name('test')->create())
                ->toThrow(InvalidArgumentException::class, 'Type is required to create FieldDTO');
        });
    });

    describe('update', function () {
        test('updates field with built data', function () {
            $expectedResponse = ['id' => '123', 'name' => 'updated_company'];

            $this->mockService->shouldReceive('update')
                ->once()
                ->with('123', Mockery::on(function (FieldDTO $dto) {
                    return $dto->name === 'updated_company' &&
                           $dto->type === 'text';
                }))
                ->andReturn($expectedResponse);

            $result = $this->builder
                ->name('updated_company')
                ->asText()
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
            $expectedResponse = ['id' => '123', 'name' => 'company'];

            $this->mockService->shouldReceive('get')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->find('123');

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('findByName', function () {
        test('delegates to service findByName method', function () {
            $expectedResponse = ['id' => '123', 'name' => 'company'];

            $this->mockService->shouldReceive('findByName')
                ->once()
                ->with('company')
                ->andReturn($expectedResponse);

            $result = $this->builder->findByName('company');

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

    describe('getUsage', function () {
        test('delegates to service getUsage method', function () {
            $expectedResponse = ['subscribers_count' => 100];

            $this->mockService->shouldReceive('getUsage')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->builder->getUsage('123');

            expect($result)->toBe($expectedResponse);
        });
    });

    describe('static factory methods', function () {
        test('text factory creates text field builder', function () {
            $builder = FieldBuilder::text('company', 'Company Name');
            $dto = $builder->toDTO();

            expect($dto->name)->toBe('company')
                ->and($dto->type)->toBe('text')
                ->and($dto->title)->toBe('Company Name');
        });

        test('number factory creates number field builder', function () {
            $builder = FieldBuilder::number('age', 'Age');
            $dto = $builder->toDTO();

            expect($dto->name)->toBe('age')
                ->and($dto->type)->toBe('number')
                ->and($dto->title)->toBe('Age');
        });

        test('date factory creates date field builder', function () {
            $builder = FieldBuilder::date('birth_date', 'Birth Date');
            $dto = $builder->toDTO();

            expect($dto->name)->toBe('birth_date')
                ->and($dto->type)->toBe('date')
                ->and($dto->title)->toBe('Birth Date');
        });

        test('boolean factory creates boolean field builder', function () {
            $builder = FieldBuilder::boolean('newsletter', 'Subscribe to Newsletter');
            $dto = $builder->toDTO();

            expect($dto->name)->toBe('newsletter')
                ->and($dto->type)->toBe('boolean')
                ->and($dto->title)->toBe('Subscribe to Newsletter');
        });

        test('select factory creates select field builder', function () {
            $options = ['Small', 'Medium', 'Large'];
            $builder = FieldBuilder::select('size', $options, 'Size');
            $dto = $builder->toDTO();

            expect($dto->name)->toBe('size')
                ->and($dto->type)->toBe('text')
                ->and($dto->title)->toBe('Size')
                ->and($dto->options)->toBe(['type' => 'select', 'values' => $options]);
        });
    });

    describe('toDTO', function () {
        test('converts builder state to DTO', function () {
            $dto = $this->builder
                ->name('company')
                ->asText()
                ->withTitle('Company Name')
                ->withDefault('Unknown')
                ->withOption('max_length', 100)
                ->required()
                ->toDTO();

            expect($dto)->toBeInstanceOf(FieldDTO::class)
                ->and($dto->name)->toBe('company')
                ->and($dto->type)->toBe('text')
                ->and($dto->title)->toBe('Company Name')
                ->and($dto->defaultValue)->toBe('Unknown')
                ->and($dto->options)->toBe(['max_length' => 100])
                ->and($dto->required)->toBeTrue();
        });

        test('throws exception when name is missing', function () {
            expect(fn() => $this->builder->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Name is required to create FieldDTO');
        });

        test('throws exception when type is missing', function () {
            expect(fn() => $this->builder->name('test')->toDTO())
                ->toThrow(InvalidArgumentException::class, 'Type is required to create FieldDTO');
        });
    });

    describe('reset', function () {
        test('resets builder to initial state', function () {
            $this->builder
                ->name('test')
                ->asText()
                ->withTitle('Title')
                ->withDefault('default')
                ->withOption('key', 'value')
                ->required();

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
                ->and($fresh)->toBeInstanceOf(FieldBuilder::class);
        });
    });

    describe('magic method handling', function () {
        test('handles "and" prefixed method calls', function () {
            $result = $this->builder
                ->name('test')
                ->andAsText()
                ->andWithTitle('Title')
                ->andRequired();

            expect($result)->toBe($this->builder);

            $dto = $this->builder->toDTO();
            expect($dto->type)->toBe('text')
                ->and($dto->title)->toBe('Title')
                ->and($dto->required)->toBeTrue();
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