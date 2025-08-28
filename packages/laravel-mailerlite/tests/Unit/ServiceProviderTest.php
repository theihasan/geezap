<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\Contracts\MailerLiteInterface;
use Ihasan\LaravelMailerlite\Contracts\SubscribersInterface;
use Ihasan\LaravelMailerlite\LaravelMailerlite;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberService;

/**
 * Service Provider Test Suite
 *
 * Tests for the Laravel service provider bindings and registrations.
 */
describe('Service Provider', function () {
    it('registers MailerLiteManager as singleton', function () {
        $manager1 = app(MailerLiteManager::class);
        $manager2 = app(MailerLiteManager::class);
        
        expect($manager1)->toBeInstanceOf(MailerLiteManager::class);
        expect($manager1)->toBe($manager2); // Same instance (singleton)
    });

    it('binds SubscribersInterface to SubscriberService', function () {
        $service = app(SubscribersInterface::class);
        
        expect($service)->toBeInstanceOf(SubscriberService::class);
    });

    it('resolves SubscriberService with correct dependencies', function () {
        $service = app(SubscriberService::class);
        
        expect($service)->toBeInstanceOf(SubscriberService::class);
    });

    it('resolves SubscriberBuilder with correct dependencies', function () {
        $builder = app(SubscriberBuilder::class);
        
        expect($builder)->toBeInstanceOf(SubscriberBuilder::class);
    });

    it('registers LaravelMailerlite as singleton', function () {
        $service1 = app(LaravelMailerlite::class);
        $service2 = app(LaravelMailerlite::class);
        
        expect($service1)->toBeInstanceOf(LaravelMailerlite::class);
        expect($service1)->toBe($service2); // Same instance (singleton)
    });

    it('binds MailerLiteInterface to LaravelMailerlite', function () {
        $service = app(MailerLiteInterface::class);
        
        expect($service)->toBeInstanceOf(LaravelMailerlite::class);
    });

    it('main service returns working subscriber builder', function () {
        $service = app(MailerLiteInterface::class);
        $builder = $service->subscribers();
        
        expect($builder)->toBeInstanceOf(SubscriberBuilder::class);
    });
});