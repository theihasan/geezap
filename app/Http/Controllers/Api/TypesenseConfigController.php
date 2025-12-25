<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseConfigController extends Controller
{
    /**
     * Get Typesense client configuration with scoped search key
     */
    public function config(): JsonResponse
    {
        try {
            $config = $this->getSecureTypesenseConfig();

            return response()->json($config);
        } catch (\Exception $e) {
            Log::error('Typesense config error', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'Unable to generate search configuration',
            ], 500);
        }
    }

    /**
     * Generate secure Typesense configuration with scoped search key
     */
    private function getSecureTypesenseConfig(): array
    {
        $cacheKey = 'typesense_search_key';
        $scopedKey = Cache::get($cacheKey);

        if (! $scopedKey) {
            $scopedKey = $this->generateScopedSearchKey();

            // Cache the scoped key for 23 hours (it expires in 24 hours)
            Cache::put($cacheKey, $scopedKey, now()->addHours(23));
        }

        return [
            'nodes' => [
                [
                    'host' => config('scout.typesense.client-settings.nodes.0.host'),
                    'port' => config('scout.typesense.client-settings.nodes.0.port'),
                    'protocol' => config('scout.typesense.client-settings.nodes.0.protocol'),
                ],
            ],
            'api_key' => $scopedKey,
            'connectionTimeoutSeconds' => config('scout.typesense.client-settings.connection_timeout_seconds'),
        ];
    }

    /**
     * Generate a scoped search key with limited permissions and TTL
     */
    private function generateScopedSearchKey(): string
    {
        $client = new Client(config('scout.typesense.client-settings'));

        $searchParameters = [
            'q' => '*',
            'query_by' => 'job_title,employer_name,description,city,state,country',
            'filter_by' => '', // Can be restricted further if needed
            'group_by' => 'job_title,employer_name',
            'group_limit' => 10,
            'per_page' => 50,
            'sort_by' => 'posted_at:desc',
        ];

        $keyData = [
            'description' => 'Search-only key for homepage suggestions',
            'actions' => ['documents:search'], // Only allow search operations
            'collections' => ['listing_index'], // Restrict to job listings collection
            'expires_at' => time() + (24 * 60 * 60), // 24 hour TTL
            'search_parameters' => $searchParameters,
        ];

        try {
            $response = $client->keys->create($keyData);

            return $response['value'];
        } catch (\Exception $e) {
            Log::error('Failed to generate scoped Typesense key', [
                'error' => $e->getMessage(),
                'key_data' => $keyData,
            ]);

            // Fallback: Check if we have a read-only key in config
            $fallbackKey = config('scout.typesense.client-settings.search_only_api_key');

            if ($fallbackKey) {
                Log::warning('Using fallback search-only key from config');

                return $fallbackKey;
            }

            // Last resort: throw exception to prevent exposing admin key
            throw new \Exception('Unable to generate secure search key and no fallback available');
        }
    }

    /**
     * Manually refresh the scoped search key (admin only)
     */
    public function refreshKey(): JsonResponse
    {
        if (! Auth::user() || Auth::user()->role !== Role::ADMIN) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Clear cached key to force regeneration
            Cache::forget('typesense_search_key');

            // Force regeneration of new scoped key
            $this->getSecureTypesenseConfig();

            return response()->json([
                'message' => 'Search key refreshed successfully',
                'key_expires_at' => now()->addHours(24)->toISOString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to refresh Typesense search key', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'Unable to refresh search key',
            ], 500);
        }
    }
}
