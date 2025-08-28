<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\GroupDTO;

describe('GroupDTO', function () {
    test('can create group DTO with required fields only', function () {
        $dto = new GroupDTO('Newsletter');

        expect($dto->name)->toBe('Newsletter')
            ->and($dto->description)->toBeNull()
            ->and($dto->tags)->toBe([])
            ->and($dto->settings)->toBe([]);
    });

    test('can create group DTO with all fields', function () {
        $dto = new GroupDTO(
            name: 'Newsletter',
            description: 'Weekly newsletter recipients',
            tags: ['important', 'weekly'],
            settings: ['double_optin' => true]
        );

        expect($dto->name)->toBe('Newsletter')
            ->and($dto->description)->toBe('Weekly newsletter recipients')
            ->and($dto->tags)->toBe(['important', 'weekly'])
            ->and($dto->settings)->toBe(['double_optin' => true]);
    });

    test('can create from array', function () {
        $data = [
            'name' => 'Newsletter',
            'description' => 'Weekly updates',
            'tags' => ['newsletter', 'weekly'],
            'settings' => ['frequency' => 'weekly']
        ];

        $dto = GroupDTO::fromArray($data);

        expect($dto->name)->toBe('Newsletter')
            ->and($dto->description)->toBe('Weekly updates')
            ->and($dto->tags)->toBe(['newsletter', 'weekly'])
            ->and($dto->settings)->toBe(['frequency' => 'weekly']);
    });

    test('throws exception when creating from array without name', function () {
        expect(fn() => GroupDTO::fromArray([]))
            ->toThrow(InvalidArgumentException::class, 'Name is required');
    });

    test('can create basic group', function () {
        $dto = GroupDTO::create('Test Group');

        expect($dto->name)->toBe('Test Group')
            ->and($dto->description)->toBeNull()
            ->and($dto->tags)->toBe([])
            ->and($dto->settings)->toBe([]);
    });

    test('can create group with description', function () {
        $dto = GroupDTO::createWithDescription('Test Group', 'Test description');

        expect($dto->name)->toBe('Test Group')
            ->and($dto->description)->toBe('Test description');
    });

    test('can create group with tags', function () {
        $dto = GroupDTO::createWithTags('Test Group', ['tag1', 'tag2']);

        expect($dto->name)->toBe('Test Group')
            ->and($dto->tags)->toBe(['tag1', 'tag2']);
    });

    test('converts to array correctly', function () {
        $dto = new GroupDTO(
            name: 'Newsletter',
            description: 'Weekly updates',
            tags: ['newsletter'],
            settings: ['frequency' => 'weekly']
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'name' => 'Newsletter',
            'description' => 'Weekly updates',
            'tags' => ['newsletter'],
            'settings' => ['frequency' => 'weekly']
        ]);
    });

    test('excludes null and empty values from array', function () {
        $dto = new GroupDTO(name: 'Newsletter');

        $array = $dto->toArray();

        expect($array)->toBe(['name' => 'Newsletter'])
            ->and($array)->not->toHaveKey('description')
            ->and($array)->not->toHaveKey('tags')
            ->and($array)->not->toHaveKey('settings');
    });

    test('can create copy with updates', function () {
        $original = GroupDTO::create('Original');
        $updated = $original->with(['description' => 'Updated description']);

        expect($original->name)->toBe('Original')
            ->and($original->description)->toBeNull()
            ->and($updated->name)->toBe('Original')
            ->and($updated->description)->toBe('Updated description');
    });

    test('can create copy with new name', function () {
        $original = GroupDTO::create('Original');
        $updated = $original->withName('Updated');

        expect($original->name)->toBe('Original')
            ->and($updated->name)->toBe('Updated');
    });

    test('can create copy with new description', function () {
        $original = GroupDTO::create('Test');
        $updated = $original->withDescription('New description');

        expect($original->description)->toBeNull()
            ->and($updated->description)->toBe('New description');
    });

    test('can create copy with additional tags', function () {
        $original = new GroupDTO(name: 'Test', tags: ['tag1']);
        $updated = $original->withTags(['tag2', 'tag3']);

        expect($original->tags)->toBe(['tag1'])
            ->and($updated->tags)->toBe(['tag1', 'tag2', 'tag3']);
    });

    test('removes duplicate tags when adding', function () {
        $original = new GroupDTO(name: 'Test', tags: ['tag1']);
        $updated = $original->withTags(['tag1', 'tag2']);

        expect($updated->tags)->toBe(['tag1', 'tag2']);
    });

    test('can create copy with additional settings', function () {
        $original = new GroupDTO(name: 'Test', settings: ['key1' => 'value1']);
        $updated = $original->withSettings(['key2' => 'value2']);

        expect($original->settings)->toBe(['key1' => 'value1'])
            ->and($updated->settings)->toBe(['key1' => 'value1', 'key2' => 'value2']);
    });

    test('validates name is not empty', function () {
        expect(fn() => new GroupDTO(''))
            ->toThrow(InvalidArgumentException::class, 'Group name cannot be empty');

        expect(fn() => new GroupDTO('   '))
            ->toThrow(InvalidArgumentException::class, 'Group name cannot be empty');
    });

    test('validates name length', function () {
        $longName = str_repeat('a', 256);

        expect(fn() => new GroupDTO($longName))
            ->toThrow(InvalidArgumentException::class, 'Group name cannot exceed 255 characters');
    });

    test('validates name does not contain invalid characters', function () {
        $invalidNames = ['Test<', 'Test>', 'Test"', "Test'", 'Test/', 'Test\\'];

        foreach ($invalidNames as $invalidName) {
            expect(fn() => new GroupDTO($invalidName))
                ->toThrow(InvalidArgumentException::class, 'Group name contains invalid characters');
        }
    });

    test('validates description length', function () {
        $longDescription = str_repeat('a', 1001);

        expect(fn() => new GroupDTO('Test', $longDescription))
            ->toThrow(InvalidArgumentException::class, 'Group description cannot exceed 1000 characters');
    });

    test('validates tags are strings', function () {
        expect(fn() => new GroupDTO('Test', null, [123]))
            ->toThrow(InvalidArgumentException::class, 'All tags must be strings');
    });

    test('validates tags are not empty', function () {
        expect(fn() => new GroupDTO('Test', null, ['']))
            ->toThrow(InvalidArgumentException::class, 'Tags cannot be empty strings');
    });

    test('validates tag length', function () {
        $longTag = str_repeat('a', 101);

        expect(fn() => new GroupDTO('Test', null, [$longTag]))
            ->toThrow(InvalidArgumentException::class, 'Each tag cannot exceed 100 characters');
    });

    test('validates setting keys are strings', function () {
        expect(fn() => new GroupDTO('Test', null, [], [123 => 'value']))
            ->toThrow(InvalidArgumentException::class, 'Setting keys must be non-empty strings');
    });

    test('validates setting keys are not empty', function () {
        expect(fn() => new GroupDTO('Test', null, [], ['' => 'value']))
            ->toThrow(InvalidArgumentException::class, 'Setting keys must be non-empty strings');
    });

    test('accepts valid setting value types', function () {
        $validSettings = [
            'string_key' => 'string_value',
            'int_key' => 123,
            'float_key' => 12.34,
            'bool_key' => true,
            'null_key' => null,
            'array_key' => ['nested' => 'value']
        ];

        $dto = new GroupDTO('Test', null, [], $validSettings);

        expect($dto->settings)->toBe($validSettings);
    });

    test('rejects invalid setting value types', function () {
        $invalidValue = new stdClass();

        expect(fn() => new GroupDTO('Test', null, [], ['key' => $invalidValue]))
            ->toThrow(InvalidArgumentException::class, "Setting 'key' has invalid value type");
    });
});