<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\FieldDTO;
use Ihasan\LaravelMailerlite\Exceptions\FieldCreateException;
use Ihasan\LaravelMailerlite\Exceptions\FieldDeleteException;
use Ihasan\LaravelMailerlite\Exceptions\FieldNotFoundException;
use Ihasan\LaravelMailerlite\Exceptions\FieldUpdateException;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Fields\FieldService;
use MailerLiteApi\MailerLite;

describe('FieldService', function () {
    beforeEach(function () {
        $this->mockClient = Mockery::mock(MailerLite::class);
        $this->mockFieldsEndpoint = Mockery::mock();
        $this->mockClient->fields = $this->mockFieldsEndpoint;

        $this->mockManager = Mockery::mock(MailerLiteManager::class);
        $this->mockManager->shouldReceive('getClient')
            ->andReturn($this->mockClient);

        $this->service = new FieldService($this->mockManager);
        $this->fieldDTO = FieldDTO::text('company', 'Company Name');
    });

    afterEach(function () {
        Mockery::close();
    });

    describe('create', function () {
        test('creates field successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'company',
                'type' => 'text',
                'title' => 'Company Name',
                'created_at' => '2023-01-01T00:00:00.000000Z'
            ];

            $this->mockFieldsEndpoint->shouldReceive('create')
                ->once()
                ->with($this->fieldDTO->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->create($this->fieldDTO);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('company')
                ->and($result['type'])->toBe('text')
                ->and($result['title'])->toBe('Company Name');
        });

        test('throws FieldCreateException when field already exists', function () {
            $exception = new Exception('Field already exists');

            $this->mockFieldsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->fieldDTO))
                ->toThrow(FieldCreateException::class, 'Failed to create field');
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockFieldsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->fieldDTO))
                ->toThrow(MailerLiteAuthenticationException::class);
        });

        test('throws FieldCreateException on validation error', function () {
            $exception = new Exception('422 Validation failed');

            $this->mockFieldsEndpoint->shouldReceive('create')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->create($this->fieldDTO))
                ->toThrow(FieldCreateException::class, 'Validation failed');
        });
    });

    describe('get', function () {
        test('gets field successfully', function () {
            $expectedResponse = [
                'id' => '123',
                'name' => 'company',
                'type' => 'text',
                'title' => 'Company Name',
                'subscribers_count' => 50
            ];

            $this->mockFieldsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->service->get('123');

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('company')
                ->and($result['type'])->toBe('text')
                ->and($result['subscribers_count'])->toBe(50);
        });

        test('returns null when field not found', function () {
            $exception = new Exception('404 Not found');

            $this->mockFieldsEndpoint->shouldReceive('find')
                ->once()
                ->with('123')
                ->andThrow($exception);

            $result = $this->service->get('123');

            expect($result)->toBeNull();
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockFieldsEndpoint->shouldReceive('find')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->get('123'))
                ->toThrow(MailerLiteAuthenticationException::class);
        });
    });

    describe('update', function () {
        test('updates field successfully', function () {
            $updateDTO = FieldDTO::text('updated_company', 'Updated Company');
            $expectedResponse = [
                'id' => '123',
                'name' => 'updated_company',
                'type' => 'text',
                'title' => 'Updated Company'
            ];

            $this->mockFieldsEndpoint->shouldReceive('update')
                ->once()
                ->with('123', $updateDTO->toArray())
                ->andReturn($expectedResponse);

            $result = $this->service->update('123', $updateDTO);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('updated_company')
                ->and($result['title'])->toBe('Updated Company');
        });

        test('throws FieldNotFoundException when field does not exist', function () {
            $updateDTO = FieldDTO::text('updated_company');
            $exception = new Exception('404 Not found');

            $this->mockFieldsEndpoint->shouldReceive('update')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->update('123', $updateDTO))
                ->toThrow(FieldNotFoundException::class, "Field with ID '123' not found");
        });

        test('throws FieldUpdateException on validation error', function () {
            $updateDTO = FieldDTO::text('updated_company');
            $exception = new Exception('422 Validation failed');

            $this->mockFieldsEndpoint->shouldReceive('update')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->update('123', $updateDTO))
                ->toThrow(FieldUpdateException::class, 'Validation failed');
        });
    });

    describe('delete', function () {
        test('deletes field successfully', function () {
            $this->mockFieldsEndpoint->shouldReceive('delete')
                ->once()
                ->with('123');

            $result = $this->service->delete('123');

            expect($result)->toBeTrue();
        });

        test('throws FieldNotFoundException when field does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockFieldsEndpoint->shouldReceive('delete')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->delete('123'))
                ->toThrow(FieldNotFoundException::class, "Field with ID '123' not found");
        });

        test('throws FieldDeleteException on other errors', function () {
            $exception = new Exception('Internal server error');

            $this->mockFieldsEndpoint->shouldReceive('delete')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->delete('123'))
                ->toThrow(FieldDeleteException::class, 'Failed to delete field');
        });
    });

    describe('list', function () {
        test('lists fields successfully', function () {
            $expectedResponse = [
                'data' => [
                    ['id' => '123', 'name' => 'company', 'type' => 'text'],
                    ['id' => '124', 'name' => 'age', 'type' => 'number']
                ],
                'meta' => ['total' => 2],
                'links' => []
            ];

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->with([])
                ->andReturn($expectedResponse);

            $result = $this->service->list();

            expect($result)->toBeArray()
                ->and($result['data'])->toHaveCount(2)
                ->and($result['data'][0]['name'])->toBe('company')
                ->and($result['data'][1]['name'])->toBe('age')
                ->and($result['meta']['total'])->toBe(2);
        });

        test('lists fields with filters', function () {
            $filters = ['limit' => 10, 'page' => 1];

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->with($filters)
                ->andReturn(['data' => [], 'meta' => [], 'links' => []]);

            $this->service->list($filters);
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->list())
                ->toThrow(MailerLiteAuthenticationException::class);
        });
    });

    describe('findByName', function () {
        test('finds field by name successfully', function () {
            $fieldsResponse = [
                'data' => [
                    ['id' => '123', 'name' => 'company', 'type' => 'text'],
                    ['id' => '124', 'name' => 'age', 'type' => 'number']
                ]
            ];

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->with([])
                ->andReturn($fieldsResponse);

            $result = $this->service->findByName('company');

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('123')
                ->and($result['name'])->toBe('company')
                ->and($result['type'])->toBe('text');
        });

        test('returns null when field not found by name', function () {
            $fieldsResponse = [
                'data' => [
                    ['id' => '124', 'name' => 'age', 'type' => 'number']
                ]
            ];

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->andReturn($fieldsResponse);

            $result = $this->service->findByName('company');

            expect($result)->toBeNull();
        });

        test('throws MailerLiteAuthenticationException on unauthorized error', function () {
            $exception = new Exception('401 Unauthorized');

            $this->mockFieldsEndpoint->shouldReceive('get')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->findByName('company'))
                ->toThrow(MailerLiteAuthenticationException::class);
        });
    });

    describe('getUsage', function () {
        test('gets field usage successfully', function () {
            $expectedResponse = [
                'subscribers_count' => 100,
                'filled_count' => 75,
                'empty_count' => 25,
                'usage_percentage' => 75.0
            ];

            $this->mockFieldsEndpoint->shouldReceive('usage')
                ->once()
                ->with('123')
                ->andReturn($expectedResponse);

            $result = $this->service->getUsage('123');

            expect($result)->toBeArray()
                ->and($result['subscribers_count'])->toBe(100)
                ->and($result['filled_count'])->toBe(75)
                ->and($result['empty_count'])->toBe(25)
                ->and($result['usage_percentage'])->toBe(75.0);
        });

        test('throws FieldNotFoundException when field does not exist', function () {
            $exception = new Exception('404 Not found');

            $this->mockFieldsEndpoint->shouldReceive('usage')
                ->once()
                ->andThrow($exception);

            expect(fn() => $this->service->getUsage('123'))
                ->toThrow(FieldNotFoundException::class, "Field with ID '123' not found");
        });

        test('handles missing usage data gracefully', function () {
            $this->mockFieldsEndpoint->shouldReceive('usage')
                ->once()
                ->andReturn([]);

            $result = $this->service->getUsage('123');

            expect($result)->toBe([
                'subscribers_count' => 0,
                'filled_count' => 0,
                'empty_count' => 0,
                'usage_percentage' => 0.0
            ]);
        });
    });

    describe('transformFieldResponse', function () {
        test('transforms response correctly with all fields', function () {
            $response = [
                'id' => '123',
                'name' => 'company',
                'type' => 'text',
                'title' => 'Company Name',
                'default_value' => 'Unknown',
                'options' => ['max_length' => 100],
                'required' => true,
                'position' => 1,
                'subscribers_count' => 50,
                'created_at' => '2023-01-01T00:00:00.000000Z',
                'updated_at' => '2023-01-01T12:00:00.000000Z'
            ];

            $this->mockFieldsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('company')
                ->and($result['type'])->toBe('text')
                ->and($result['title'])->toBe('Company Name')
                ->and($result['default_value'])->toBe('Unknown')
                ->and($result['options'])->toBe(['max_length' => 100])
                ->and($result['required'])->toBeTrue()
                ->and($result['position'])->toBe(1)
                ->and($result['subscribers_count'])->toBe(50)
                ->and($result['created_at'])->toBe('2023-01-01T00:00:00.000000Z');
        });

        test('transforms response with missing fields to defaults', function () {
            $response = ['id' => '123', 'name' => 'company', 'type' => 'text'];

            $this->mockFieldsEndpoint->shouldReceive('find')
                ->once()
                ->andReturn($response);

            $result = $this->service->get('123');

            expect($result['id'])->toBe('123')
                ->and($result['name'])->toBe('company')
                ->and($result['type'])->toBe('text')
                ->and($result['title'])->toBeNull()
                ->and($result['default_value'])->toBeNull()
                ->and($result['options'])->toBe([])
                ->and($result['required'])->toBeFalse()
                ->and($result['position'])->toBeNull()
                ->and($result['subscribers_count'])->toBeNull()
                ->and($result['created_at'])->toBeNull();
        });
    });
});