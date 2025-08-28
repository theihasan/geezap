<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\Facades\MailerLite;
use Ihasan\LaravelMailerlite\LaravelMailerlite;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;

/**
 * MailerLite Facade Test Suite
 *
 * Tests for the MailerLite facade functionality and delegation.
 */
describe('MailerLite Facade', function () {
    it('resolves subscribers builder through facade', function () {
        $builder = MailerLite::subscribers();

        expect($builder)->toBeInstanceOf(SubscriberBuilder::class);
    });

    it('returns different builder instances on multiple calls', function () {
        $builder1 = MailerLite::subscribers();
        $builder2 = MailerLite::subscribers();

        expect($builder1)->toBeInstanceOf(SubscriberBuilder::class);
        expect($builder2)->toBeInstanceOf(SubscriberBuilder::class);
        expect($builder1)->not->toBe($builder2); // Different instances
    });

    it('facade accessor points to correct service', function () {
        $reflection = new ReflectionClass(MailerLite::class);
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);
        $accessor = $method->invoke(null);

        expect($accessor)->toBe(LaravelMailerlite::class);
    });
});
