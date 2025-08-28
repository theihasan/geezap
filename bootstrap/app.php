<?php

use App\Jobs\AspJob;
use App\Jobs\ReactJob;
use App\Jobs\VueJsJob;
use App\Jobs\NodeJSJob;
use App\Jobs\GetJobData;
use App\Jobs\LaravelJob;
use App\Jobs\PaythonJob;
use App\Jobs\SymfonyJob;
use App\Jobs\WordPressJob;
use App\Jobs\ResetAPIKeyLimit;
use App\Jobs\CollectMetricsJob;
use Illuminate\Support\Facades\Artisan;
use Sentry\Laravel\Integration;
use App\Jobs\DispatchJobCategories;
use App\Jobs\NotifyUserAboutNewJobs;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\PrometheusMiddleware;
use App\Http\Middleware\BlockCrawlerMiddleware;
use App\Http\Middleware\CaptureCloudflareCountry;
use App\Http\Middleware\VerifyClouflareTurnstile;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(BlockCrawlerMiddleware::class);
        $middleware->web(append: [
            CaptureCloudflareCountry::class,
            PrometheusMiddleware::class,
        ]);
        $middleware->alias([
            'cf-turnstile.verify' => VerifyClouflareTurnstile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->withSchedule(function (Schedule $schedule) {
        $schedule->job(new DispatchJobCategories())
            ->everySixHours()
            //->days([Schedule::FRIDAY, Schedule::SUNDAY, Schedule::TUESDAY, Schedule::THURSDAY])
            ->withoutOverlapping(600);

        $schedule->job(new ResetAPIKeyLimit())
            ->monthly()
            ->withoutOverlapping(600);

        $schedule->job(new NotifyUserAboutNewJobs())
            ->daily()
            ->days([Schedule::SATURDAY, Schedule::THURSDAY])
            ->withoutOverlapping(600);
            
        $schedule->command('backup:run --only-db')
            ->everySixHours()
            ->then(function () {
                Artisan::call('backup:clean');
            });

        // $schedule->job(new CollectMetricsJob('business'))
        //     ->everyFiveMinutes()
        //     ->withoutOverlapping(300);

        // $schedule->job(new CollectMetricsJob('system'))
        //     ->everyTenMinutes()
        //     ->withoutOverlapping(600);

        $schedule->command('model:prune')->everyMinute();

    })->create();
