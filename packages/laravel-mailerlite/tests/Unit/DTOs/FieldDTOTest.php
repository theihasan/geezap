<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\FieldDTO;

describe('FieldDTO', function () {
    test('can create field DTO with required fields only', function () {
        $dto = new FieldDTO('age', 'number');

        expect($dto->name)->toBe('age')
            ->and($dto->type)->toBe('number')
            ->and($dto->title)->toBeNull()
            ->and($dto->defaultValue)->toBeNull()
            ->and($dto->options)->toBe([])
            ->and($dto->required)->toBeFalse();
    });

    test('can create field DTO with all fields', function () {
        $dto = new FieldDTO(
            name: 'user_age',
            type: 'number',
            title: 'User Age',
            defaultValue: 18,
            options: ['min_value' => 0, 'max_value' => 120],
            required: true
        );

        expect($dto->name)->toBe('user_age')
            ->and($dto->type)->toBe('number')
            ->and($dto->title)->toBe('User Age')
            ->and($dto->defaultValue)->toBe(18)
            ->and($dto->options)->toBe(['min_value' => 0, 'max_value' => 120])
            ->and($dto->required)->toBeTrue();
    });

    test('can create from array', function () {
        $data = [
            'name' => 'company',
            'type' => 'text',
            'title' => 'Company Name',
            'default_value' => 'Unknown',
            'options' => ['max_length' => 100],
            'required' => false
        ];

        $dto = FieldDTO::fromArray($data);

        expect($dto->name)->toBe('company')
            ->and($dto->type)->toBe('text')
            ->and($dto->title)->toBe('Company Name')
            ->and($dto->defaultValue)->toBe('Unknown')
            ->and($dto->options)->toBe(['max_length' => 100])
            ->and($dto->required)->toBeFalse();
    });

    test('throws exception when creating from array without name', function () {
        expect(fn() => FieldDTO::fromArray(['type' => 'text']))
            ->toThrow(InvalidArgumentException::class, 'Name is required');
    });

    test('throws exception when creating from array without type', function () {
        expect(fn() => FieldDTO::fromArray(['name' => 'test']))
            ->toThrow(InvalidArgumentException::class, 'Type is required');
    });

    test('can create text field', function () {
        $dto = FieldDTO::text('company', 'Company Name', 'Unknown');

        expect($dto->name)->toBe('company')
            ->and($dto->type)->toBe('text')
            ->and($dto->title)->toBe('Company Name')
            ->and($dto->defaultValue)->toBe('Unknown');
    });

    test('can create number field', function () {
        $dto = FieldDTO::number('age', 'Age', 25);

        expect($dto->name)->toBe('age')
            ->and($dto->type)->toBe('number')
            ->and($dto->title)->toBe('Age')
            ->and($dto->defaultValue)->toBe(25);
    });

    test('can create date field', function () {
        $dto = FieldDTO::date('birth_date', 'Birth Date', '2000-01-01');

        expect($dto->name)->toBe('birth_date')
            ->and($dto->type)->toBe('date')
            ->and($dto->title)->toBe('Birth Date')
            ->and($dto->defaultValue)->toBe('2000-01-01');
    });

    test('can create boolean field', function () {
        $dto = FieldDTO::boolean('newsletter', 'Subscribe to Newsletter', true);

        expect($dto->name)->toBe('newsletter')
            ->and($dto->type)->toBe('boolean')
            ->and($dto->title)->toBe('Subscribe to Newsletter')
            ->and($dto->defaultValue)->toBeTrue();
    });

    test('can create select field', function () {
        $options = ['Small', 'Medium', 'Large'];
        $dto = FieldDTO::select('size', $options, 'Size', 'Medium');

        expect($dto->name)->toBe('size')
            ->and($dto->type)->toBe('text')
            ->and($dto->title)->toBe('Size')
            ->and($dto->defaultValue)->toBe('Medium')
            ->and($dto->options)->toBe(['type' => 'select', 'values' => $options]);
    });

    test('converts to array correctly', function () {
        $dto = new FieldDTO(
            name: 'age',
            type: 'number',
            title: 'Age',
            defaultValue: 25,
            options: ['min_value' => 0],
            required: true
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'name' => 'age',
            'type' => 'number',
            'title' => 'Age',
            'default_value' => 25,
            'options' => ['min_value' => 0],
            'required' => true
        ]);
    });

    test('excludes null and false values from array', function () {
        $dto = new FieldDTO(name: 'test', type: 'text');

        $array = $dto->toArray();

        expect($array)->toBe(['name' => 'test', 'type' => 'text'])
            ->and($array)->not->toHaveKey('title')
            ->and($array)->not->toHaveKey('default_value')
            ->and($array)->not->toHaveKey('options')
            ->and($array)->not->toHaveKey('required');
    });

    test('can create copy with updates', function () {
        $original = FieldDTO::text('name');
        $updated = $original->with(['title' => 'Full Name', 'required' => true]);

        expect($original->name)->toBe('name')
            ->and($original->title)->toBeNull()
            ->and($original->required)->toBeFalse()
            ->and($updated->name)->toBe('name')
            ->and($updated->title)->toBe('Full Name')
            ->and($updated->required)->toBeTrue();
    });

    test('can create copy with new name', function () {
        $original = FieldDTO::text('old_name');
        $updated = $original->withName('new_name');

        expect($original->name)->toBe('old_name')
            ->and($updated->name)->toBe('new_name');
    });

    test('can create copy with new title', function () {
        $original = FieldDTO::text('name');
        $updated = $original->withTitle('Full Name');

        expect($original->title)->toBeNull()
            ->and($updated->title)->toBe('Full Name');
    });

    test('can create copy with new default value', function () {
        $original = FieldDTO::text('name');
        $updated = $original->withDefaultValue('John Doe');

        expect($original->defaultValue)->toBeNull()
            ->and($updated->defaultValue)->toBe('John Doe');
    });

    test('can create copy with additional options', function () {
        $original = new FieldDTO(name: 'name', type: 'text', options: ['key1' => 'value1']);
        $updated = $original->withOptions(['key2' => 'value2']);

        expect($original->options)->toBe(['key1' => 'value1'])
            ->and($updated->options)->toBe(['key1' => 'value1', 'key2' => 'value2']);
    });

    test('can create copy marked as required', function () {
        $original = FieldDTO::text('name');
        $updated = $original->required();

        expect($original->required)->toBeFalse()
            ->and($updated->required)->toBeTrue();
    });

    test('can create copy marked as optional', function () {
        $original = new FieldDTO('name', 'text', required: true);
        $updated = $original->optional();

        expect($original->required)->toBeTrue()
            ->and($updated->required)->toBeFalse();
    });

    test('validates name is not empty', function () {
        expect(fn() => new FieldDTO('', 'text'))
            ->toThrow(InvalidArgumentException::class, 'Field name cannot be empty');

        expect(fn() => new FieldDTO('   ', 'text'))
            ->toThrow(InvalidArgumentException::class, 'Field name cannot be empty');
    });

    test('validates name length', function () {
        $longName = str_repeat('a', 101);

        expect(fn() => new FieldDTO($longName, 'text'))
            ->toThrow(InvalidArgumentException::class, 'Field name cannot exceed 100 characters');
    });

    test('validates name format', function () {
        $invalidNames = ['123invalid', 'invalid-name', 'invalid.name', 'invalid name'];

        foreach ($invalidNames as $invalidName) {
            expect(fn() => new FieldDTO($invalidName, 'text'))
                ->toThrow(InvalidArgumentException::class, 'Field name must start with a letter');
        }
    });

    test('accepts valid name formats', function () {
        $validNames = ['name', 'user_name', 'userName', 'name123', 'a', 'field_1'];

        foreach ($validNames as $validName) {
            $dto = new FieldDTO($validName, 'text');
            expect($dto->name)->toBe($validName);
        }
    });

    test('validates field type', function () {
        $invalidTypes = ['string', 'int', 'float', 'array', 'invalid'];

        foreach ($invalidTypes as $invalidType) {
            expect(fn() => new FieldDTO('name', $invalidType))
                ->toThrow(InvalidArgumentException::class, 'Invalid field type');
        }
    });

    test('accepts valid field types', function () {
        $validTypes = ['text', 'number', 'date', 'boolean'];

        foreach ($validTypes as $validType) {
            $dto = new FieldDTO('name', $validType);
            expect($dto->type)->toBe($validType);
        }
    });

    test('validates title length', function () {
        $longTitle = str_repeat('a', 256);

        expect(fn() => new FieldDTO('name', 'text', $longTitle))
            ->toThrow(InvalidArgumentException::class, 'Field title cannot exceed 255 characters');
    });

    test('validates default value matches field type', function () {
        expect(fn() => new FieldDTO('name', 'text', null, 123))
            ->toThrow(InvalidArgumentException::class, 'Default value for text field must be a string');

        expect(fn() => new FieldDTO('age', 'number', null, 'not a number'))
            ->toThrow(InvalidArgumentException::class, 'Default value for number field must be numeric');

        expect(fn() => new FieldDTO('active', 'boolean', null, 'not a boolean'))
            ->toThrow(InvalidArgumentException::class, 'Default value for boolean field must be a boolean');

        expect(fn() => new FieldDTO('date', 'date', null, 123))
            ->toThrow(InvalidArgumentException::class, 'Default value for date field must be a string');

        expect(fn() => new FieldDTO('date', 'date', null, 'invalid-date'))
            ->toThrow(InvalidArgumentException::class, 'Default value for date field must be in YYYY-MM-DD format');
    });

    test('accepts valid default values for each type', function () {
        $dto1 = new FieldDTO('name', 'text', null, 'John');
        expect($dto1->defaultValue)->toBe('John');

        $dto2 = new FieldDTO('age', 'number', null, 25);
        expect($dto2->defaultValue)->toBe(25);

        $dto3 = new FieldDTO('age', 'number', null, 25.5);
        expect($dto3->defaultValue)->toBe(25.5);

        $dto4 = new FieldDTO('active', 'boolean', null, true);
        expect($dto4->defaultValue)->toBeTrue();

        $dto5 = new FieldDTO('date', 'date', null, '2023-01-01');
        expect($dto5->defaultValue)->toBe('2023-01-01');
    });

    test('validates options keys are strings', function () {
        expect(fn() => new FieldDTO('name', 'text', null, null, [123 => 'value']))
            ->toThrow(InvalidArgumentException::class, 'Option keys must be non-empty strings');
    });

    test('validates options keys are not empty', function () {
        expect(fn() => new FieldDTO('name', 'text', null, null, ['' => 'value']))
            ->toThrow(InvalidArgumentException::class, 'Option keys must be non-empty strings');
    });

    test('accepts valid option value types', function () {
        $validOptions = [
            'string_key' => 'string_value',
            'int_key' => 123,
            'float_key' => 12.34,
            'bool_key' => true,
            'null_key' => null,
            'array_key' => ['nested' => 'value']
        ];

        $dto = new FieldDTO('name', 'text', null, null, $validOptions);

        expect($dto->options)->toBe($validOptions);
    });

    test('rejects invalid option value types', function () {
        $invalidValue = new stdClass();

        expect(fn() => new FieldDTO('name', 'text', null, null, ['key' => $invalidValue]))
            ->toThrow(InvalidArgumentException::class, "Option 'key' has invalid value type");
    });
});