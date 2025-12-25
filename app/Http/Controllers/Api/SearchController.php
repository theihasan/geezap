<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchSuggestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct(
        private SearchSuggestionService $searchService
    ) {}

    /**
     * Get search suggestions for autocomplete
     */
    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        try {
            $suggestions = $this->searchService->getSuggestions($query, $limit);

            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => $suggestions,
                    'query' => $query,
                    'total' => count($suggestions),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Search suggestions error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load suggestions',
                'data' => ['suggestions' => []],
            ], 500);
        }
    }

    /**
     * Track a search query
     */
    public function track(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'results_count' => 'nullable|integer|min:0',
            'filters' => 'nullable|array',
        ]);

        try {
            $this->searchService->trackSearch([
                'query' => $request->query,
                'user_id' => Auth::id(),
                'results_count' => $request->results_count ?? 0,
                'filters' => $request->filters ?? [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Search tracked successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Search tracking error', [
                'query' => $request->query,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to track search',
            ], 500);
        }
    }

    /**
     * Get search statistics (admin only)
     */
    public function stats(): JsonResponse
    {
        // Simple admin check - you might want to implement proper role checking
        if (! Auth::user() || Auth::user()->email !== 'admin@example.com') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $stats = $this->searchService->getSearchStats();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Search stats error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load statistics',
            ], 500);
        }
    }

    /**
     * Get recent searches for logged-in user
     */
    public function recent(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        try {
            $recentSearches = \App\Models\SearchAnalytics::where('user_id', Auth::id())
                ->orderBy('searched_at', 'desc')
                ->limit(10)
                ->pluck('query')
                ->unique()
                ->values()
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'searches' => $recentSearches,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Recent searches error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load recent searches',
            ], 500);
        }
    }
}