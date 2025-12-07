<?php

namespace App\Http\Controllers;

use App\Enums\SocialProvider;
use App\Models\User;
use App\Services\ProfileImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController extends Controller
{
    public function __construct(
        private ProfileImageService $profileImageService
    ) {}

    public function redirect(string $provider): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)
            ->redirect();
    }

    public function callback(string $provider): \Illuminate\Http\RedirectResponse
    {
        try{
            $providerResponse = Socialite::driver($provider)->user();

            $user = User::query()->updateOrCreate(
                ['email' => $providerResponse->getEmail()],
                ['password' => Hash::make(Str::password(8))],
            );

            $data = [$provider . '_id' => $providerResponse->getId()];

            switch ($provider){
                case SocialProvider::GITHUB->value:
                    $data['github_token'] = $providerResponse->token ?? '';
                    $data['twitter'] = $providerResponse->user['twitter_username'] ?? '';
                    break;

                case SocialProvider::FACEBOOK->value:
                    $data['facebook_token'] = $providerResponse->token ?? '';
                    break;

                case SocialProvider::GOOGLE->value:
                    $data['google_token'] = $providerResponse->token ?? '';
                    break;

                default: break;
            }


            if ($user->wasRecentlyCreated) {
                $data['name'] = $providerResponse->getName() ?? $providerResponse->getNickname();
                $data['bio'] = $providerResponse->user['bio'] ?? '';

                $this->handleProfileImage($user, $providerResponse, $provider);

                event(new Registered($user));
            } else {
                if (empty($user->profile_image)) {
                    $this->handleProfileImage($user, $providerResponse, $provider);
                }
            }

            $user->update($data);

            Auth::login($user, remember: true);
            $user->update(['last_login_at' => now()]);
            
            if ($user->wasRecentlyCreated || !$user->onboarding_completed_at) {
                return redirect()->route('onboarding.welcome');
            }
            
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            logger('Error on social login: ' . $e->getMessage());
            return redirect()->route('login')->with(['status' => $e->getMessage()]);
        }
    }

    /**
     * Handle profile image download and saving for social authentication
     */
    private function handleProfileImage(User $user, $providerUser, string $provider): void
    {
        try {
            $avatarUrl = $providerUser->getAvatar();
            
            if ($avatarUrl) {
                $imagePath = $this->profileImageService->downloadAndSaveProfileImage($avatarUrl, $provider, $user->id);
                
                if ($imagePath) {
                    $user->update(['profile_image' => $imagePath]);
                }
            }
        } catch (\Exception $e) {
            logger("Failed to save profile image for user {$user->id}: " . $e->getMessage());
        }
    }
}
