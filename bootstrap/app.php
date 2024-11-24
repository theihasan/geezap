<?php

use App\Jobs\AspJob;
use App\Jobs\GetJobData;
use App\Jobs\LaravelJob;
use App\Jobs\NodeJSJob;
use App\Jobs\PaythonJob;
use App\Jobs\ReactJob;
use App\Jobs\SymfonyJob;
use App\Jobs\VueJsJob;
use App\Jobs\WordPressJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
        $schedule->job(new GetJobData())->everyMinute();

    })->create();
