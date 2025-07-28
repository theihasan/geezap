<?php

namespace App\Providers;

use Prometheus\Storage\Redis;
use Prometheus\CollectorRegistry;
use Illuminate\Support\ServiceProvider;

class PrometheusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(CollectorRegistry::class, function ($app) {
            $redis = new Redis([
                'host' => config('database.redis.default.host'),
                'port' => config('database.redis.default.port'),
                'password' => config('database.redis.default.password'),
                'timeout' => 0.1, 
                'read_timeout' => '10',
                'persistent_connections' => false
            ]);
            
            return new CollectorRegistry($redis);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
