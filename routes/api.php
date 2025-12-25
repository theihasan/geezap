<?php

use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/typesense/config', function () {
    return response()->json([
        'nodes' => [
            [
                'host' => config('scout.typesense.client-settings.nodes.0.host'),
                'port' => config('scout.typesense.client-settings.nodes.0.port'),
                'protocol' => config('scout.typesense.client-settings.nodes.0.protocol'),
            ],
        ],
        'api_key' => config('scout.typesense.client-settings.api_key'), // Note: This should be a read-only key in production
        'connectionTimeoutSeconds' => config('scout.typesense.client-settings.connection_timeout_seconds'),
    ]);
});

// Search API routes
Route::prefix('search')->middleware('api.throttle')->group(function () {
    Route::get('suggestions', [SearchController::class, 'suggestions']); // Re-enabled for fallback/testing
    Route::post('track', [SearchController::class, 'track']);
    Route::get('recent', [SearchController::class, 'recent'])->middleware('auth:sanctum');
    Route::get('stats', [SearchController::class, 'stats'])->middleware('auth:sanctum');
});
