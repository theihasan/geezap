<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\Facades\MailerLite;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;

/**
 * Fluent API Integration Test Suite
 *
 * Tests for the complete fluent API integration including facades,
 * builders, and method chaining working together.
 */
describe('Fluent API Integration', function () {
    it('provides fluent subscriber API through facade', function () {
        $builder = MailerLite::subscribers()
            ->email('integration@example.com')
            ->named('Integration Test User')
            ->withField('source', 'integration_test')
            ->toGroup('test-group');

        expect($builder)->toBeInstanceOf(SubscriberBuilder::class);

        $dto = $builder->toDTO();
        expect($dto->email)->toBe('integration@example.com');
        expect($dto->name)->toBe('Integration Test User');
        expect($dto->fields)->toBe(['source' => 'integration_test']);
        expect($dto->groups)->toBe(['test-group']);
    });

    it('supports natural language method chaining', function () {
        $builder = MailerLite::subscribers()
            ->email('natural@example.com')
            ->andNamed('Natural User')
            ->andWithFields(['company' => 'Test Corp'])
            ->andToGroups(['developers', 'newsletter'])
            ->andImported()
            ->resubscribeIfExists();

        $dto = $builder->toDTO();
        expect($dto->email)->toBe('natural@example.com');
        expect($dto->name)->toBe('Natural User');
        expect($dto->fields)->toBe(['company' => 'Test Corp']);
        expect($dto->groups)->toBe(['developers', 'newsletter']);
        expect($dto->type)->toBe('imported');
        expect($dto->resubscribe)->toBeTrue();
    });

    it('allows builder reuse and reset', function () {
        $builder = MailerLite::subscribers()
            ->email('first@example.com')
            ->named('First User');

        $firstDto = $builder->toDTO();
        expect($firstDto->email)->toBe('first@example.com');
        expect($firstDto->name)->toBe('First User');

        // Reset and reuse
        $builder->reset()
            ->email('second@example.com')
            ->named('Second User');

        $secondDto = $builder->toDTO();
        expect($secondDto->email)->toBe('second@example.com');
        expect($secondDto->name)->toBe('Second User');
    });

    it('supports creating fresh builder instances', function () {
        $builder1 = MailerLite::subscribers()
            ->email('builder1@example.com');

        $builder2 = $builder1->fresh()
            ->email('builder2@example.com');

        expect($builder1)->not->toBe($builder2);
        expect($builder1->toDTO()->email)->toBe('builder1@example.com');
        expect($builder2->toDTO()->email)->toBe('builder2@example.com');
    });

    it('demonstrates complete fluent workflow', function () {
        // This test demonstrates the complete fluent API workflow
        // Note: This doesn't actually call MailerLite API, just demonstrates the fluent interface

        $builder = MailerLite::subscribers()
            ->email('workflow@example.com')
            ->named('Workflow User')
            ->withField('role', 'admin')
            ->withField('department', 'engineering')
            ->toGroups(['admins', 'engineers', 'newsletter'])
            ->imported()
            ->resubscribeIfExists()
            ->withAutoresponders();

        // Verify all the fluent operations worked
        $dto = $builder->toDTO();

        expect($dto->email)->toBe('workflow@example.com');
        expect($dto->name)->toBe('Workflow User');
        expect($dto->fields)->toBe([
            'role' => 'admin',
            'department' => 'engineering',
        ]);
        expect($dto->groups)->toBe(['admins', 'engineers', 'newsletter']);
        expect($dto->type)->toBe('imported');
        expect($dto->resubscribe)->toBeTrue();
        expect($dto->autoresponders)->toBeTrue();
        expect($dto->status)->toBe('active'); // default
    });

    it('validates required fields appropriately', function () {
        $builder = MailerLite::subscribers()
            ->named('No Email User')
            ->withField('test', 'value');

        // Should throw exception when trying to create DTO without email
        expect(fn () => $builder->toDTO())
            ->toThrow(InvalidArgumentException::class, 'Email is required');
    });
});
