<?php

use App\Http\Middleware\BlockCrawlerMiddleware;
use App\Http\Middleware\CaptureCloudflareCountry;
use App\Http\Middleware\VerifyClouflareTurnstile;
use App\Jobs\AspJob;
use App\Jobs\DispatchJobCategories;
use App\Jobs\GetJobData;
use App\Jobs\LaravelJob;
use App\Jobs\NodeJSJob;
use App\Jobs\NotifyUserAboutNewJobs;
use App\Jobs\PaythonJob;
use App\Jobs\ReactJob;
use App\Jobs\ResetAPIKeyLimit;
use App\Jobs\SymfonyJob;
use App\Jobs\VueJsJob;
use App\Jobs\WordPressJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(BlockCrawlerMiddleware::class);
        $middleware->append(CaptureCloudflareCountry::class);
        $middleware->alias([
            'cf-turnstile.verify' => VerifyClouflareTurnstile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->withSchedule(function (Schedule $schedule) {
        $schedule->job(new DispatchJobCategories())
            ->daily()
            ->withoutOverlapping(600);

        $schedule->job(new ResetAPIKeyLimit())
            ->monthly()
            ->withoutOverlapping(600);

        $schedule->job(new NotifyUserAboutNewJobs())
            ->daily()
            ->withoutOverlapping(600);

        $schedule->command('model:prune')->everyMinute();

    })->create();
