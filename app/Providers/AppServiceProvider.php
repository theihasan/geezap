<?php

namespace App\Providers;

use App\Enums\ApiName;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        try {
            Http::macro('job', function () {
                $apiKey = DB::table('api_keys')
                    ->where('api_name', '=', ApiName::JOB->value)
                    ->orderByDesc('request_remaining')
                    ->first();

                return Http::withHeaders([
                    'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com',
                    'X-RapidAPI-Key' => $apiKey->api_key,
                ])->baseUrl('https://jsearch.p.rapidapi.com');
            });
        } catch (\Exception $e){
            logger('Error on app service provider', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }

        Carbon::macro('dayWithSuffix', function () {
            $day = $this->day;
            if (!in_array(($day % 100), [11, 12, 13])) {
                switch ($day % 10) {
                    case 1: return $day . 'st';
                    case 2: return $day . 'nd';
                    case 3: return $day . 'rd';
                }
            }
            return $day . 'th';
        });

    }
}
