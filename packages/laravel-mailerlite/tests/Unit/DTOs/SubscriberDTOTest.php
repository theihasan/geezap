<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\DTOs\SubscriberDTO;
use InvalidArgumentException;

/**
 * SubscriberDTO Test Suite
 *
 * Tests for the SubscriberDTO class including validation,
 * creation methods, and data transformation.
 */
describe('SubscriberDTO', function () {
    describe('Basic Creation', function () {
        it('creates with valid email', function () {
            $dto = new SubscriberDTO('john@example.com');

            expect($dto->email)->toBe('john@example.com');
            expect($dto->name)->toBeNull();
            expect($dto->fields)->toBe([]);
            expect($dto->groups)->toBe([]);
            expect($dto->status)->toBe('active');
            expect($dto->resubscribe)->toBeFalse();
            expect($dto->autoresponders)->toBeTrue();
        });

        it('creates with email and name', function () {
            $dto = new SubscriberDTO('jane@example.com', 'Jane Doe');

            expect($dto->email)->toBe('jane@example.com');
            expect($dto->name)->toBe('Jane Doe');
        });

        it('creates with all parameters', function () {
            $dto = new SubscriberDTO(
                email: 'test@example.com',
                name: 'Test User',
                fields: ['company' => 'Acme Inc'],
                groups: ['123', '456'],
                status: 'unconfirmed',
                resubscribe: true,
                type: 'imported',
                segments: ['789'],
                autoresponders: false
            );

            expect($dto->email)->toBe('test@example.com');
            expect($dto->name)->toBe('Test User');
            expect($dto->fields)->toBe(['company' => 'Acme Inc']);
            expect($dto->groups)->toBe(['123', '456']);
            expect($dto->status)->toBe('unconfirmed');
            expect($dto->resubscribe)->toBeTrue();
            expect($dto->type)->toBe('imported');
            expect($dto->segments)->toBe(['789']);
            expect($dto->autoresponders)->toBeFalse();
        });
    });

    describe('Factory Methods', function () {
        it('creates from array', function () {
            $data = [
                'email' => 'user@example.com',
                'name' => 'User Name',
                'fields' => ['role' => 'admin'],
                'groups' => ['group1'],
                'status' => 'active',
            ];

            $dto = SubscriberDTO::fromArray($data);

            expect($dto->email)->toBe('user@example.com');
            expect($dto->name)->toBe('User Name');
            expect($dto->fields)->toBe(['role' => 'admin']);
            expect($dto->groups)->toBe(['group1']);
            expect($dto->status)->toBe('active');
        });

        it('creates basic subscriber', function () {
            $dto = SubscriberDTO::create('basic@example.com', 'Basic User');

            expect($dto->email)->toBe('basic@example.com');
            expect($dto->name)->toBe('Basic User');
            expect($dto->groups)->toBe([]);
            expect($dto->fields)->toBe([]);
        });

        it('creates with groups', function () {
            $dto = SubscriberDTO::createWithGroups(
                'group@example.com',
                'Group User',
                ['group1', 'group2']
            );

            expect($dto->email)->toBe('group@example.com');
            expect($dto->name)->toBe('Group User');
            expect($dto->groups)->toBe(['group1', 'group2']);
        });

        it('creates with fields', function () {
            $fields = ['company' => 'Tech Corp', 'role' => 'Developer'];
            $dto = SubscriberDTO::createWithFields('field@example.com', 'Field User', $fields);

            expect($dto->email)->toBe('field@example.com');
            expect($dto->name)->toBe('Field User');
            expect($dto->fields)->toBe($fields);
        });
    });

    describe('Email Validation', function () {
        it('throws exception for empty email', function () {
            expect(fn () => new SubscriberDTO(''))
                ->toThrow(InvalidArgumentException::class, 'Email cannot be empty');
        });

        it('throws exception for whitespace-only email', function () {
            expect(fn () => new SubscriberDTO('   '))
                ->toThrow(InvalidArgumentException::class, 'Email cannot be empty');
        });

        it('throws exception for invalid email format', function () {
            expect(fn () => new SubscriberDTO('invalid-email'))
                ->toThrow(InvalidArgumentException::class, 'Invalid email address');
        });

        it('throws exception for disposable email', function () {
            expect(fn () => new SubscriberDTO('test@10minutemail.com'))
                ->toThrow(InvalidArgumentException::class, 'Disposable email addresses are not allowed');
        });

        it('accepts valid email formats', function () {
            $validEmails = [
                'simple@example.com',
                'user.name@example.com',
                'user+tag@example.com',
                'user123@example-domain.com',
                'test@subdomain.example.org',
            ];

            foreach ($validEmails as $email) {
                $dto = new SubscriberDTO($email);
                expect($dto->email)->toBe($email);
            }
        });
    });

    describe('Status Validation', function () {
        it('accepts valid statuses', function () {
            $validStatuses = ['active', 'unsubscribed', 'unconfirmed', 'bounced', 'junk'];

            foreach ($validStatuses as $status) {
                $dto = new SubscriberDTO('test@example.com', status: $status);
                expect($dto->status)->toBe($status);
            }
        });

        it('throws exception for invalid status', function () {
            expect(fn () => new SubscriberDTO('test@example.com', status: 'invalid'))
                ->toThrow(InvalidArgumentException::class, 'Invalid status');
        });
    });

    describe('Type Validation', function () {
        it('accepts valid types', function () {
            $validTypes = ['regular', 'unsubscribed', 'imported'];

            foreach ($validTypes as $type) {
                $dto = new SubscriberDTO('test@example.com', type: $type);
                expect($dto->type)->toBe($type);
            }
        });

        it('throws exception for invalid type', function () {
            expect(fn () => new SubscriberDTO('test@example.com', type: 'invalid'))
                ->toThrow(InvalidArgumentException::class, 'Invalid type');
        });

        it('accepts null type', function () {
            $dto = new SubscriberDTO('test@example.com', type: null);
            expect($dto->type)->toBeNull();
        });
    });

    describe('Fields Validation', function () {
        it('accepts valid field types', function () {
            $fields = [
                'string_field' => 'text',
                'int_field' => 42,
                'float_field' => 3.14,
                'bool_field' => true,
                'null_field' => null,
            ];

            $dto = new SubscriberDTO('test@example.com', fields: $fields);
            expect($dto->fields)->toBe($fields);
        });

        it('throws exception for non-string keys', function () {
            expect(fn () => new SubscriberDTO('test@example.com', fields: [123 => 'value']))
                ->toThrow(InvalidArgumentException::class, 'Field keys must be non-empty strings');
        });

        it('throws exception for empty string keys', function () {
            expect(fn () => new SubscriberDTO('test@example.com', fields: ['' => 'value']))
                ->toThrow(InvalidArgumentException::class, 'Field keys must be non-empty strings');
        });

        it('throws exception for invalid field values', function () {
            expect(fn () => new SubscriberDTO('test@example.com', fields: ['key' => ['array']]))
                ->toThrow(InvalidArgumentException::class, 'Field \'key\' has invalid value type');
        });
    });

    describe('Groups Validation', function () {
        it('accepts string and integer group IDs', function () {
            $dto = new SubscriberDTO('test@example.com', groups: ['123', 456, 'group-name']);
            expect($dto->groups)->toBe(['123', 456, 'group-name']);
        });

        it('throws exception for invalid group ID types', function () {
            expect(fn () => new SubscriberDTO('test@example.com', groups: [123.45]))
                ->toThrow(InvalidArgumentException::class, 'Group IDs must be strings or integers');
        });

        it('throws exception for empty string group IDs', function () {
            expect(fn () => new SubscriberDTO('test@example.com', groups: ['']))
                ->toThrow(InvalidArgumentException::class, 'Group IDs cannot be empty strings');
        });
    });

    describe('Array Conversion', function () {
        it('converts to array with minimal data', function () {
            $dto = new SubscriberDTO('test@example.com');
            $array = $dto->toArray();

            expect($array)->toBe(['email' => 'test@example.com']);
        });

        it('converts to array with all data', function () {
            $dto = new SubscriberDTO(
                email: 'full@example.com',
                name: 'Full User',
                fields: ['role' => 'admin'],
                groups: ['group1'],
                status: 'unconfirmed',
                resubscribe: true,
                type: 'imported',
                segments: ['segment1'],
                autoresponders: false
            );

            $expected = [
                'email' => 'full@example.com',
                'name' => 'Full User',
                'fields' => ['role' => 'admin'],
                'groups' => ['group1'],
                'status' => 'unconfirmed',
                'resubscribe' => true,
                'type' => 'imported',
                'segments' => ['segment1'],
                'autoresponders' => false,
            ];

            expect($dto->toArray())->toBe($expected);
        });

        it('excludes default values from array', function () {
            $dto = new SubscriberDTO(
                email: 'default@example.com',
                name: 'User',
                status: 'active', // default
                resubscribe: false, // default
                autoresponders: true // default
            );

            $expected = [
                'email' => 'default@example.com',
                'name' => 'User',
            ];

            expect($dto->toArray())->toBe($expected);
        });
    });

    describe('Immutable Updates', function () {
        it('creates copy with updates using with method', function () {
            $original = new SubscriberDTO('original@example.com', 'Original');
            $updated = $original->with(['name' => 'Updated', 'status' => 'unconfirmed']);

            expect($original->name)->toBe('Original');
            expect($original->status)->toBe('active');
            expect($updated->name)->toBe('Updated');
            expect($updated->status)->toBe('unconfirmed');
            expect($updated->email)->toBe('original@example.com');
        });

        it('creates copy with new name', function () {
            $original = new SubscriberDTO('test@example.com', 'Original Name');
            $updated = $original->withName('New Name');

            expect($original->name)->toBe('Original Name');
            expect($updated->name)->toBe('New Name');
            expect($updated->email)->toBe($original->email);
        });

        it('creates copy with additional groups', function () {
            $original = new SubscriberDTO('test@example.com', groups: ['group1']);
            $updated = $original->withGroups(['group2', 'group3']);

            expect($original->groups)->toBe(['group1']);
            expect($updated->groups)->toBe(['group1', 'group2', 'group3']);
        });

        it('creates copy with additional fields', function () {
            $original = new SubscriberDTO('test@example.com', fields: ['field1' => 'value1']);
            $updated = $original->withFields(['field2' => 'value2', 'field1' => 'updated']);

            expect($original->fields)->toBe(['field1' => 'value1']);
            expect($updated->fields)->toBe(['field1' => 'updated', 'field2' => 'value2']);
        });
    });

    describe('Error Cases', function () {
        it('throws exception when creating from array without email', function () {
            expect(fn () => SubscriberDTO::fromArray(['name' => 'No Email']))
                ->toThrow(InvalidArgumentException::class, 'Email is required');
        });

        it('validates all constraints when using with method', function () {
            $dto = new SubscriberDTO('test@example.com');

            expect(fn () => $dto->with(['status' => 'invalid_status']))
                ->toThrow(InvalidArgumentException::class, 'Invalid status');
        });
    });
});
