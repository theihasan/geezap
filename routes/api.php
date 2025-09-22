<?php

use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Search API routes
Route::prefix('search')->middleware('api.throttle')->group(function () {
    Route::get('suggestions', [SearchController::class, 'suggestions']);
    Route::post('track', [SearchController::class, 'track']);
    Route::get('recent', [SearchController::class, 'recent'])->middleware('auth:sanctum');
    Route::get('stats', [SearchController::class, 'stats'])->middleware('auth:sanctum');
});
