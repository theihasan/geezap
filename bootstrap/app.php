<?php

use App\Jobs\AspJob;
use App\Jobs\DispatchJobCategories;
use App\Jobs\ReactJob;
use App\Jobs\ResetAPIKeyLimit;
use App\Jobs\VueJsJob;
use App\Jobs\NodeJSJob;
use App\Jobs\GetJobData;
use App\Jobs\LaravelJob;
use App\Jobs\PaythonJob;
use App\Jobs\SymfonyJob;
use App\Jobs\WordPressJob;
use Sentry\Laravel\Integration;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\VerifyClouflareTurnstile;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'cf-turnstile.verify' => VerifyClouflareTurnstile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->withSchedule(function (Schedule $schedule) {
        $schedule->job(new DispatchJobCategories())->daily();
        $schedule->job(new ResetAPIKeyLimit())->monthly()->withoutOverlapping(600);
        $schedule->command('model:prune')->everyMinute();

    })->create();
