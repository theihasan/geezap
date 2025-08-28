<?php

namespace Ihasan\LaravelMailerlite;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ihasan\LaravelMailerlite\Commands\LaravelMailerliteCommand;

class LaravelMailerliteServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mailerlite')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_mailerlite_table')
            ->hasCommand(LaravelMailerliteCommand::class);
    }
}
