<?php

namespace App\Providers;

use App\Services\AI\BaseAIService;
use App\Services\AI\OpenAIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BaseAIService::class, OpenAIService::class);
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerOpenAIMacro();
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
}
