<?php

namespace App\Providers;

use App\Enums\Role;
use App\Events\ExceptionHappenEvent;
use App\Listeners\MetricsEventListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureUrl();
        $this->registerHttpMacros();
        $this->configureCommand();
        $this->configureMetaTags();
        // $this->configureRateLimiter();
        $this->configureLogViewer();
        // $this->registerMetricsEventListeners();
        Livewire::component('job-filter', \App\Livewire\JobFilter::class);

    }

    private function configureUrl(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function registerHttpMacros(): void
    {
        try {

            $this->registerJobMacro();
            $this->registerOpenAIMacro();
        } catch (\Exception $e) {
            logger('Error on app service provider', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    private function configureCommand(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureMetaTags(): void
    {
        try {
            View::composer('v2.partials.header', function ($view) {
                if (! $view->offsetExists('meta')) {
                    $seoService = app(\App\Services\SeoMetaService::class);
                    $meta = $seoService->generateMeta();
                    $view->with('meta', $meta);
                }
            });
        } catch (\Exception $e) {
            logger('Error configuring meta tags', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    private function registerJobMacro(): void
    {
        Http::macro('job', function () {
            $apiKeyService = app(\App\Services\ApiKeyService::class);
            $apiKey = $apiKeyService->getAvailableApiKey();

            if (! $apiKey) {
                throw new \RuntimeException('No available API key for job requests');
            }

            logger('API Key for request', [
                'API Key' => $apiKey->api_key,
                'Request Remaining' => $apiKey->request_remaining,
                'Sent Request' => $apiKey->sent_request,
                'Usage Ratio' => $apiKey->request_remaining > 0 ? round($apiKey->sent_request / $apiKey->request_remaining, 3) : 'N/A',
            ]);

            $httpMacro = Http::withHeaders([
                'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com',
                'X-RapidAPI-Key' => $apiKey->api_key,
            ])->baseUrl('https://jsearch.p.rapidapi.com');

            $apiKey->touch('request_sent_at');

            return $httpMacro;
        });
    }

    private function registerOpenAIMacro(): void
    {
        Http::macro('openai', function () {
            $apiKey = config('ai.chat_gpt_api_key');

            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$apiKey,
            ])->baseUrl('https://api.openai.com/v1/chat');
        });
    }

    private function configureRateLimiter(): void
    {
        RateLimiter::for('weeklyemail', function () {
            return Limit::perMinute(50);
        });
    }

    private function configureLogViewer()
    {
        LogViewer::auth(function () {
            return auth()->check() && auth()->user()->role === Role::ADMIN;
        });
    }

    private function registerMetricsEventListeners(): void
    {
        Event::listen(Registered::class, [MetricsEventListener::class, 'handleUserRegistered']);
        Event::listen(Login::class, [MetricsEventListener::class, 'handleUserLogin']);

        Event::listen(ExceptionHappenEvent::class, [MetricsEventListener::class, 'handleExceptionHappen']);
        Event::listen(JobProcessed::class, [MetricsEventListener::class, 'handleJobProcessed']);
        Event::listen(JobFailed::class, [MetricsEventListener::class, 'handleJobFailed']);
        Event::listen(MessageSent::class, [MetricsEventListener::class, 'handleMessageSent']);
        Event::listen(NotificationSent::class, [MetricsEventListener::class, 'handleNotificationSent']);
    }
}
