<?php

use Illuminate\Support\Facades\Route;
use Geezap\ContentFormatter\Http\Controllers\ContentFormatterController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/geezap/content-formatter', [ContentFormatterController::class, 'index'])
        ->middleware('can:admin-access')
        ->name('content-formatter.index');
    
    Route::post('/geezap/content-formatter', [ContentFormatterController::class, 'store'])
        ->middleware('can:admin-access')
        ->name('content-formatter.store');
});