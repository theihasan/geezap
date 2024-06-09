<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/jobs', function () {
    return view('job.index');
})->name('job.index');

Route::get('/categories', function () {
    return view('job.categories');
})->name('job.categories');

Route::get('contact', function () {
    return view('contact');
})->name('contact');

Route::get('/dashboard', function () {
    return view('profile.profile');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile-update', function () {
    return view('profile.profile-setting');
})->middleware(['auth', 'verified'])->name('profile.update');

Route::middleware('auth')->group(function () {
    Route::post('personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('personal-info.update');
    Route::post('contact-info', [ProfileController::class, 'updateContactInfo'])->name('contact-info.update');
    Route::post('password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('social-media-info', [ProfileController::class, 'updateSocialMediaInfo'])->name('social-media-info.update');

});

require __DIR__.'/auth.php';
