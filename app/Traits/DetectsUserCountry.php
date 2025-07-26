<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

trait DetectsUserCountry
{
    /**
     * Get user's country from authenticated user or CloudFlare header with caching
     */
    protected function getUserCountry(): ?string
    {
        $cacheKey = $this->getCountryCacheKey();
        
        return Cache::remember($cacheKey, 60 * 60, function () {
            if (Auth::check() && Auth::user()->country) {
                return Auth::user()->country;
            }

            $cfCountry = request()->header('CF-IPCountry');
            
            return $this->normalizeCountryCode($cfCountry);
        });
    }

    /**
     * Generate cache key for country detection
     */
    private function getCountryCacheKey(): string
    {
        if (Auth::check()) {
            return "user_country_" . Auth::id();
        }
        
        $ipHash = md5(request()->ip());
        return "guest_country_{$ipHash}";
    }

    /**
     * Normalize country code to match database format
     */
    private function normalizeCountryCode(?string $countryCode): ?string
    {
        if (!$countryCode) {
            return null;
        }

        return strtoupper($countryCode);
    }

    /**
     * Clear user country cache (useful when user updates profile)
     */
    protected function clearUserCountryCache(): void
    {
        $cacheKey = $this->getCountryCacheKey();
        Cache::forget($cacheKey);
    }

    /**
     * Set user country in cache (useful for manual setting)
     */
    protected function setUserCountryCache(string $country): void
    {
        $cacheKey = $this->getCountryCacheKey();
        Cache::put($cacheKey, $this->normalizeCountryCode($country), 60 * 60);
    }
} 