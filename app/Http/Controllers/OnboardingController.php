<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Country;

class OnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the welcome step of onboarding.
     */
    public function welcome(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.welcome', compact('user'));
    }

    /**
     * Show the essential info step of onboarding.
     */
    public function essentialInfo(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        $countries = Country::query()->where('is_active', true)->orderBy('name')->get();

        return view('onboarding.essential-info', compact('user', 'countries'));
    }

    /**
     * Store essential info and redirect to preferences.
     */
    public function storeEssentialInfo(Request $request): RedirectResponse
    {
        $request->validate([
            'occupation' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $country = Country::query()->find($request->country_id);
        
        $user->update([
            'occupation' => $request->occupation,
            'country' => $country->name, 
            'bio' => $request->bio,
        ]);

        $user->refresh();
        $this->updateProfileCompletionScore($user);

        return redirect()->route('onboarding.preferences');
    }

    /**
     * Show the preferences step of onboarding.
     */
    public function preferences(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if ($user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        $preferences = $user->userPreference;
        if (!$preferences) {
            $preferences = new UserPreference();
        }

        return view('onboarding.preferences', compact('user', 'preferences'));
    }

    /**
     * Store preferences and complete onboarding.
     */
    public function storePreferences(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'job_alerts' => 'boolean',
            'newsletter' => 'boolean',
            'marketing_emails' => 'boolean',
        ]);

        $user = Auth::user();

        $preferences = $user->preferences ?: new UserPreference();
        $preferences->fill([
            'user_id' => $user->id,
            'email_notifications' => $request->boolean('email_notifications', true),
            'job_alerts' => $request->boolean('job_alerts', false),
            'newsletter' => $request->boolean('newsletter', true),
            'marketing_emails' => $request->boolean('marketing_emails', false),
        ]);

        if (!$preferences->exists) {
            $user->preferences()->save($preferences);
        } else {
            $preferences->save();
        }

        $user->update([
            'onboarding_completed_at' => now(),
        ]);

        $this->updateProfileCompletionScore($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to Geezap! Your account is now set up.');
    }

    /**
     * Skip onboarding and go directly to dashboard.
     */
    public function skip(): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update([
            'onboarding_completed_at' => now(),
        ]);

        $this->updateProfileCompletionScore($user);

        return redirect()->route('dashboard')->with('info', 'You can complete your profile anytime from your settings.');
    }

    /**
     * Calculate and update user's profile completion score.
     */
    private function updateProfileCompletionScore(User $user): void
    {
        if ($user->onboarding_completed_at) {
            $user->update(['profile_completion_score' => 100]);
            return;
        }

        // During onboarding, calculate based on required fields only
        $requiredFields = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'occupation' => !empty($user->occupation),
            'country' => !empty($user->country),
        ];

        $optionalFields = [
            'bio' => !empty($user->bio),
            'phone' => !empty($user->phone),
            'website' => !empty($user->website),
            'skills' => !empty($user->skills),
        ];

        $completedRequired = array_filter($requiredFields);
        $completedOptional = array_filter($optionalFields);
        
        // Required fields are worth 75%, optional are worth 25%
        $requiredScore = (count($completedRequired) / count($requiredFields)) * 75;
        $optionalScore = (count($completedOptional) / count($optionalFields)) * 25;
        $score = round($requiredScore + $optionalScore);

        $user->update(['profile_completion_score' => $score]);
    }
}
