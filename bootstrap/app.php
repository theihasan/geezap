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
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
//        $schedule->job(new AspJob)->everyMinute();
//        $schedule->job(new LaravelJob)->everyMinute();
//        $schedule->job(new NodeJSJob)->everyMinute();
//        $schedule->job(new PaythonJob)->everyMinute();
//        $schedule->job(new ReactJob)->everyMinute();
//        $schedule->job(new SymfonyJob)->everyMinute();
//        $schedule->job(new VueJsJob)->everyMinute();
//        $schedule->job(new WordPressJob)->everyMinute();
        $schedule->job(new GetJobData())->everyMinute();

    })->create();
