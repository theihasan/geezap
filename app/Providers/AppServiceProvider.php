<?php

namespace App\Providers;

use App\DTO\DiscordCardDTO;
use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\TwitterCardDTO;
use App\Enums\ApiName;
use App\Models\ApiKey;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        $this->configureRateLimiter();

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
            $this->registerLinkedInJobMacro();
            $this->registerOpenAIMacro();
        } catch (\Exception $e) {
            logger('Error on app service provider', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    private function registerJobMacro(): void
    {
        Http::macro('job', function () {
            $apiKey = ApiKey::query()->where('api_name', ApiName::JOB)
                ->orderBy('sent_request')
                ->first();

            logger('API Key for request', [
                'API Key' => $apiKey->api_key,
                'Request Remaining' => $apiKey->request_remaining
            ]);

            $apiKey->increment('sent_request');

            return Http::withHeaders([
                'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com',
                'X-RapidAPI-Key' => $apiKey->api_key,
            ])->baseUrl('https://jsearch.p.rapidapi.com');
        });
    }

    private function registerLinkedInJobMacro(): void
    {
        Http::macro('linkedinjob', function () {
            $apiKey = ApiKey::query()->where('api_name', ApiName::LINKEDIN)
                ->orderBy('sent_request')
                ->first();

            logger('API Key for request', [
                'API Key' => $apiKey->api_key,
                'Request Remaining' => $apiKey->request_remaining
            ]);

            $apiKey->increment('sent_request');

            return Http::withHeaders([
                'x-rapidapi-host' => 'linkedin-job-search-api.p.rapidapi.com',
                'x-rapidapi-key' => $apiKey->api_key,
            ])->baseUrl('https://linkedin-job-search-api.p.rapidapi.com');
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

    private function configureCommand(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureMetaTags(): void
    {
        try {
            View::composer('v2.partials.header', function ($view) {
                if (! $view->offsetExists('meta')) {
                    $currentRoute = request()->route()?->getName();

                    $meta = match ($currentRoute) {
                        'dashboard' => new MetaTagDTO(
                            title: 'Dashboard | '.config('app.name', 'Geezap'),
                            description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                            keywords: 'dashboard, job applications, career tracking, job management',
                            og: new OpenGraphDTO(
                                title: 'Dashboard | '.config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                type: 'website',
                                image: asset('assets/images/favicon.ico')
                            ),
                            twitter: new TwitterCardDTO(
                                title: 'Dashboard | '.config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                image: asset('assets/images/favicon.ico')
                            ),
                            discord: new DiscordCardDTO(
                                title: 'Dashboard | '.config('app.name', 'Geezap'),
                                description: 'Manage your job applications, track your progress, and update your preferences on Geezap.',
                                image: asset('assets/images/favicon.ico')
                            )
                        ),

                        'job.index' => new MetaTagDTO(
                            title: 'Browse Jobs | '.config('app.name', 'Geezap'),
                            description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                            keywords: 'tech jobs, remote work, full-time positions, contract work',
                            og: new OpenGraphDTO(
                                title: 'Browse Jobs | '.config('app.name', 'Geezap'),
                                description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                                type: 'website',
                                image: asset('assets/images/favicon.ico')
                            ),
                            twitter: new TwitterCardDTO(
                                title: 'Browse Jobs | '.config('app.name', 'Geezap'),
                                description: 'Explore thousands of tech jobs from top companies. Find remote, full-time, and contract positions.',
                                image: asset('assets/images/favicon.ico')
                            ),
                            discord: new DiscordCardDTO(
                                title: 'Browse Jobs | '.config('app.name', 'Geezap'),
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

    private function configureRateLimiter(): void
    {
        RateLimiter::for('weeklyemail', function () {
            return Limit::perMinute(50);
        });
    }


}
