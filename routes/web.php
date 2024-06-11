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

Route::get('/profile-update', [ProfileController::class, 'edit'])->middleware(['auth'])->name('profile.update');

Route::middleware('auth')->group(function () {
    Route::post('personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('personal-info.update');
    Route::post('contact-info', [ProfileController::class, 'updateContactInfo'])->name('contact-info.update');
    Route::post('password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('social-media-info', [ProfileController::class, 'updateSocialMediaInfo'])->name('social-media-info.update');
    Route::post('experience', [ProfileController::class, 'updateExperience'])->name('experience.update');
    Route::post('skill', [ProfileController::class, 'updateSkill'])->name('skill.update');

});

require __DIR__.'/auth.php';
