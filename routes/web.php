<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CoverLetterController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobPreferencesController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('about', \App\Http\Controllers\Pages\AboutPageController::class)->name('about');
Route::get('contact', \App\Http\Controllers\Pages\ContactPageController::class)->name('contact');
Route::get('privacy-policy', \App\Http\Controllers\Pages\PrivacyPolicyPageController::class)->name('privacy-policy');
Route::get('terms', \App\Http\Controllers\Pages\TermsPageController::class)->name('terms');
Route::get('job-preferences', [JobPreferencesController::class, 'index'])->name('job.preferences');

Route::prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('job.index');
    Route::get('/{slug}', [JobController::class, 'job'])->name('job.show');
});

Route::get('/categories', JobCategoryController::class)->name('job.categories');

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'onboarding.required'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding/welcome', [OnboardingController::class, 'welcome'])->name('onboarding.welcome');
    Route::get('/onboarding/essential-info', [OnboardingController::class, 'essentialInfo'])->name('onboarding.essential-info');
    Route::post('/onboarding/essential-info', [OnboardingController::class, 'storeEssentialInfo'])->name('onboarding.essential-info.store');
    Route::get('/onboarding/preferences', [OnboardingController::class, 'preferences'])->name('onboarding.preferences');
    Route::post('/onboarding/preferences', [OnboardingController::class, 'storePreferences'])->name('onboarding.preferences.store');
    Route::post('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip.post');
});

Route::middleware(['auth', 'onboarding.required'])->group(function () {
    Route::get('/profile-update', [ProfileController::class, 'edit'])
        ->name('profile.update');
    Route::post('personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('personal-info.update');
    Route::post('contact-info', [ProfileController::class, 'updateContactInfo'])->name('contact-info.update');
    Route::post('password', [ProfileController::class, 'updatePassword'])->name('userpassword.update');
    Route::post('social-media-info', [ProfileController::class, 'updateSocialMediaInfo'])->name('social-media-info.update');
    Route::post('experience', [ProfileController::class, 'updateExperience'])->name('experience.update');
    Route::post('skill', [ProfileController::class, 'updateSkill'])->name('skill.update');
    Route::delete('profile/image', [ProfileController::class, 'removeProfileImage'])->name('profile.image.remove');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications');
    Route::get('/cover-letter', [CoverLetterController::class, 'update'])->name('cover-letter.update');
    Route::get('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
});

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

Route::get('/metrics', [\App\Http\Controllers\MetricsController::class, 'index'])->name('metrics');
Route::get('/health', [\App\Http\Controllers\MetricsController::class, 'health'])->name('health');

require __DIR__.'/auth.php';
