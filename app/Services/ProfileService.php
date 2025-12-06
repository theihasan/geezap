<?php

namespace App\Services;

use App\Http\Requests\ContactInfoUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\PersonalInfoUpdateRequest;
use App\Http\Requests\SocialMediaInfoUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileService
{
    public function __construct(
        private ProfileImageService $profileImageService
    ) {}

    public function updatePersonalInfo(PersonalInfoUpdateRequest $request, User $user): void
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $this->profileImageService->deleteOldProfileImage($user->profile_image);
            }
            
            $imagePath = $this->uploadProfileImage($request->file('profile_image'), $user->id);
            if ($imagePath) {
                $data['profile_image'] = $imagePath;
            }
        }
        
        $user->update($data);
    }
    
    /**
     * Upload profile image to storage
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $userId
     * @return string|null
     */
    private function uploadProfileImage($file, $userId): ?string
    {
        try {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = "user_{$userId}_" . time() . "_" . uniqid() . ".{$extension}";
            
            // Store the file in public disk under profile-images folder
            $filePath = $file->storeAs('profile-images', $filename, 'public');
            
            if ($filePath) {
                return $filePath;
            }
            
        } catch (\Exception $e) {
            \Log::error("Failed to upload profile image for user {$userId}", [
                'error' => $e->getMessage()
            ]);
        }
        
        return null;
    }

    public function updateContactInfo(ContactInfoUpdateRequest $request, User $user): void
    {
        $data = $request->validated();
        $user->update([
            'phone' => $data['phone'],
            'website' => $data['website'],
        ]);
    }

    public function updatePassword(PasswordUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        if(!Hash::check($data['current_password'], $user->password)) {
            return Redirect::route('profile.update')->with('status', 'Current password is incorrect');
        }
        $user->update([
            'password' => bcrypt($data['password']),
        ]);
    }

    public function updateSocialMediaInfo(SocialMediaInfoUpdateRequest $request, User $user): void
    {
        $data = $request->validated();

        $socialPlatforms = [
            'facebook' => 'https://www.facebook.com/',
            'twitter' => 'https://www.twitter.com/',
            'linkedin' => 'https://www.linkedin.com/',
            'github' => 'https://www.github.com/'
        ];

        foreach ($socialPlatforms as $platform => $baseUrl) {
            if (!empty($data[$platform]) && !str_contains($data[$platform], "$platform.com")) {
                $data[$platform] = $baseUrl . $data[$platform];
            }
        }

        $user->update([
            'facebook' => $data['facebook'] ?? '',
            'twitter' => $data['twitter'] ?? '',
            'linkedin' => $data['linkedin'] ?? '',
            'github' => $data['github'] ?? '',
        ]);
    }

}
