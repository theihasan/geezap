<?php

namespace App\Http\Controllers;

use App\Enums\SocialProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController extends Controller
{
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

                event(new Registered($user));
            }

            $user->update($data);

            Auth::login($user, remember: true);
            $user->update(['last_login_at' => now()]);
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            logger('Error on social login: ' . $e->getMessage());
            return redirect()->route('login')->with(['status' => $e->getMessage()]);
        }
    }
}
