<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Enums\Role;
use App\Enums\ApiName;
use App\Models\ApiKey;
use Livewire\Livewire;
use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\DiscordCardDTO;
use App\DTO\TwitterCardDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Opcodes\LogViewer\Facades\LogViewer;
use Illuminate\Support\Facades\RateLimiter;
use App\Listeners\MetricsEventListener;
use Illuminate\Auth\Events\Registered;
use App\Events\CoverLetterGenerated;
use App\Events\ExceptionHappenEvent;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;

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
        //$this->configureUrl();
        $this->registerHttpMacros();
        $this->configureCommand();
        $this->configureMetaTags();
        //$this->configureRateLimiter();
        $this->configureLogViewer();
        $this->registerMetricsEventListeners();
        Livewire::component('job-filter', \App\Livewire\JobFilter::class);

    }

    private function configureUrl(): void
    {
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function registerHttpMacros(): void
    {
        try {

            $this->registerJobMacro();
            $this->registerOpenAIMacro();
        } catch (\Exception $e){
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
                if (!$view->offsetExists('meta')) {
                    $currentRoute = request()->route()?->getName();

                    $meta = match($currentRoute) {
                        'dashboard' => new MetaTagDTO(
                            title: 'Dashboard | ' . config('app.name', 'Geezap'),
                            description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                            keywords: 'dashboard, job applications, career tracking, job management',
                            og: new OpenGraphDTO(
                                title: 'Dashboard | ' . config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                type: 'website',
                                image: asset('assets/images/favicon.ico')
                            ),
                            twitter: new TwitterCardDTO(
                                title: 'Dashboard | ' . config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                image: asset('assets/images/favicon.ico')
                            ),
                            discord: new DiscordCardDTO(
                                title: 'Dashboard | ' . config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                image: asset('assets/images/favicon.ico')
                            )
                        ),

                        'job.index' => new MetaTagDTO(
                            title: 'Browse Jobs | ' . config('app.name', 'Geezap'),
                            description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                            keywords: 'tech jobs, remote work, full-time positions, contract work',
                            og: new OpenGraphDTO(
                                title: 'Browse Jobs | ' . config('app.name', 'Geezap'),
                                description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                                type: 'website',
                                image: asset('assets/images/favicon.ico')
                            ),
                            twitter: new TwitterCardDTO(
                                title: 'Browse Jobs | ' . config('app.name', 'Geezap'),
                                description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                                image: asset('assets/images/favicon.ico')
                            ),
                            discord: new DiscordCardDTO(
                                title: 'Browse Jobs | ' . config('app.name', 'Geezap'),
                                description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                                image: asset('assets/images/favicon.ico')
                            )
                        ),

                        default => new MetaTagDTO(
                            title: config('app.name', 'Geezap'),
                            description: 'Find your dream job with Geezap - AI-powered job aggregation platform unifying listings from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart matching and cover letter generation.',
                            keywords: 'job search, AI job matching, career platform, job aggregator, remote jobs, tech jobs, LinkedIn jobs, Upwork, Indeed, ZipRecruiter',
                            og: new OpenGraphDTO(
                                title: config('app.name', 'Geezap'),
                                description: 'Find your dream job with Geezap - AI-powered job aggregation platform unifying listings from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart matching and cover letter generation.',
                                type: 'website',
                                image: asset('assets/images/favicon.ico')
                            ),
                            twitter: new TwitterCardDTO(
                                title: config('app.name', 'Geezap'),
                                description: 'Find your dream job with Geezap - AI-powered job aggregation platform unifying listings from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart matching and cover letter generation.',
                                image: asset('assets/images/favicon.ico')
                            ),
                            discord: new DiscordCardDTO(
                                title: config('app.name', 'Geezap'),
                                description: 'Find your dream job with Geezap - AI-powered job aggregation platform unifying listings from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart matching and cover letter generation.',
                                image: asset('assets/images/favicon.ico')
                            )
                        ),
                    };

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
            $apiKey = ApiKey::query()
                ->where('api_name', ApiName::JOB)
                // ->where(function($query) {
                //     $query->whereNull('rate_limit_reset')
                //         ->orWhere('rate_limit_reset', '>', Carbon::now());
                // })
                ->orderBy('sent_request')
                ->first();

            logger('API Key for request', [
                'API Key' => $apiKey->api_key,
                'Request Remaining' => $apiKey->request_remaining
            ]);

            $apiKey->increment('sent_request');

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
                'Authorization' => 'Bearer ' . $apiKey,
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
        Event::listen(CoverLetterGenerated::class, [MetricsEventListener::class, 'handleCoverLetterGenerated']);
        Event::listen(ExceptionHappenEvent::class, [MetricsEventListener::class, 'handleExceptionHappen']);
        Event::listen(JobProcessed::class, [MetricsEventListener::class, 'handleJobProcessed']);
        Event::listen(JobFailed::class, [MetricsEventListener::class, 'handleJobFailed']);
        Event::listen(MessageSent::class, [MetricsEventListener::class, 'handleMessageSent']);
        Event::listen(NotificationSent::class, [MetricsEventListener::class, 'handleNotificationSent']);
    }


}
