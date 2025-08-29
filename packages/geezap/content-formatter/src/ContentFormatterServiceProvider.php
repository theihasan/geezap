<?php

namespace Geezap\ContentFormatter;

use App\Enums\Role;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class ContentFormatterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'content-formatter');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/content-formatter'),
        ], 'content-formatter-views');

        Gate::define('admin-access', function ($user) {
            return $user->role === Role::ADMIN->value;
        });
    }

    public function register()
    {
        //
    }
}