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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
