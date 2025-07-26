<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateUserCountryFromCloudflare implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $userId,
        private readonly ?string $cfCountry
    ) {}

    public function handle(): void
    {
        if (empty($this->cfCountry)) {
            return;
        }

        $user = User::find($this->userId);
        
        if (!$user) {
            return;
        }

        $normalizedCountry = strtoupper($this->cfCountry);

        if ($user->country === $normalizedCountry) {
            return;
        }

        $user->update([
            'country' => $normalizedCountry
        ]);

        $this->clearUserCountryCache($this->userId);
    }

    /**
     * Clear user's country cache after updating
     */
    private function clearUserCountryCache(string $userId): void
    {
        $cacheKey = "user_country_{$userId}";
        Cache::forget($cacheKey);
    }
}