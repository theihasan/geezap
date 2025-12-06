<?php

namespace App\Http\Controllers;

use App\Enums\Timezone;
use App\Models\Country;
use Illuminate\View\View;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Services\SeoMetaService;
use App\Caches\JobRecommendationCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateSkillRequest;
use App\Services\JobRecommendationService;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Http\Requests\ContactInfoUpdateRequest;
use App\Http\Requests\PersonalInfoUpdateRequest;
use App\Http\Requests\UserPreferencesUpdateRequest;
use App\Http\Requests\SocialMediaInfoUpdateRequest;
use App\Services\ProfileImageService;

class ProfileController extends Controller
{
    public function __construct(
        protected JobRecommendationService $jobRecommendationService,
        protected SeoMetaService $seoService
    ) {
       
    }

    public function edit(): View
    {   
        $experiences = json_decode(Auth::user()->experience, true);
        $skills = json_decode(Auth::user()->skills, true);
        $timezones = Timezone::cases();
        $meta = $this->seoService->generateMeta();

        return view('v2.profile.edit-profile', [
            'experiences' => $experiences,
            'skills' => $skills,
            'timezones' => $timezones,
            'meta' => $meta
        ]);
    }

    public function updatePersonalInfo(PersonalInfoUpdateRequest $request, ProfileService $profileService): RedirectResponse
    {
         $profileService->updatePersonalInfo($request, Auth::user());
         return Redirect::route('profile.update')->with('status', 'Profile updated successfully');
    }

    public function updateContactInfo(ContactInfoUpdateRequest $request, ProfileService $profileService): RedirectResponse
    {
        $profileService->updateContactInfo($request, Auth::user());
        return Redirect::route('profile.update')->with('status', 'Contact info updated successfully');
    }

    public function updatePassword(PasswordUpdateRequest $request, ProfileService $profileService): RedirectResponse
    {
        $profileService->updatePassword($request, Auth::user());
        return Redirect::route('profile.update')->with('status', 'Password updated successfully');
    }

    public function updateSocialMediaInfo(SocialMediaInfoUpdateRequest $request, ProfileService $profileService): RedirectResponse
    {
        $profileService->updateSocialMediaInfo($request, Auth::user());
        return Redirect::route('profile.update')->with('status', 'Social media info updated successfully');
    }

    public function updateExperience(UpdateExperienceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $user->update([
            'experience' =>  json_encode($data)
        ]);

        return Redirect::route('profile.update')->with('status', 'Experience updated successfully');
    }

    public function updateSkill(UpdateSkillRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $user->update([
            'skills' =>  json_encode($data)
        ]);

        return Redirect::route('profile.update')->with('status', 'Skills updated successfully');
    }

    public function destroy(): RedirectResponse
    {
        $user = Auth::user();
        $user->delete();

        return Redirect::route('home')->with('success', 'Profile deleted successfully');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $recommendedJobs = $this->jobRecommendationService->getRecommendedJobsForUser($user, 6);
        $meta = $this->seoService->generateMeta();
        
        return view('v2.profile.profile', compact('recommendedJobs', 'meta'));
    }

    public function preferences(): View
    {
        $user = auth()->user();
        $jobCategories = JobCategory::query()->orderBy('name')->get();
        $countries = Country::query()->orderBy('name')->get();
        
        $preferences = $user->preferences;
        $recommendedJobs = $this->jobRecommendationService->getRecommendedJobsForUser($user, 6);
        $meta = $this->seoService->generateMeta();
        
        return view('v2.profile.preferences', compact('jobCategories', 'countries', 'preferences', 'recommendedJobs', 'meta'));
    }

    public function updatePreferences(UserPreferencesUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();
        
        $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            $request->getPreferencesData()
        );

        JobRecommendationCache::invalidateUserRecommendations($user->id);

        return redirect()->route('profile.preferences')->with('success', 'Preferences updated successfully!');
    }

    /**
     * Remove user's profile image
     */
    public function removeProfileImage(ProfileImageService $profileImageService)
    {
        $user = Auth::user();
        
        try {
            if ($user->profile_image) {
                $profileImageService->deleteOldProfileImage($user->profile_image);
                $user->update(['profile_image' => null]);
                
                return response()->json(['success' => true, 'message' => 'Profile image removed successfully']);
            }
            
            return response()->json(['success' => false, 'message' => 'No profile image to remove']);
            
        } catch (\Exception $e) {
            \Log::error('Error removing profile image for user ' . $user->id, [
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Error removing profile image']);
        }
    }
}
