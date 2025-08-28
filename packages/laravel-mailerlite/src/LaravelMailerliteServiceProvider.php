<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite;

use Ihasan\LaravelMailerlite\Contracts\MailerLiteInterface;
use Ihasan\LaravelMailerlite\Contracts\SubscribersInterface;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Laravel MailerLite Service Provider
 *
 * Registers all services, binds contracts to implementations,
 * and configures the package for Laravel integration.
 */
class LaravelMailerliteServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package settings.
     *
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mailerlite')
            ->hasConfigFile('mailerlite');
    }

    /**
     * Register package services and bindings.
     *
     * @return void
     */
    public function packageRegistered(): void
    {
        // Register the MailerLite Manager as singleton
        $this->app->singleton(MailerLiteManager::class, function ($app) {
            $config = $app['config']['mailerlite'];
            return MailerLiteManager::fromConfig($config);
        });

        // Register subscriber service
        $this->app->bind(SubscribersInterface::class, SubscriberService::class);
        $this->app->bind(SubscriberService::class, function ($app) {
            return new SubscriberService($app->make(MailerLiteManager::class));
        });

        // Register subscriber builder
        $this->app->bind(SubscriberBuilder::class, function ($app) {
            return new SubscriberBuilder($app->make(SubscriberService::class));
        });

        // Register main service
        $this->app->singleton(LaravelMailerlite::class, function ($app) {
            return new LaravelMailerlite($app->make(MailerLiteManager::class));
        });

        // Bind main interface
        $this->app->bind(MailerLiteInterface::class, LaravelMailerlite::class);
    }

    /**
     * Boot package services.
     *
     * @return void
     */
    public function packageBooted(): void
    {
        // Any additional booting logic can go here
    }
}
