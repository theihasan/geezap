<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                ['password' => Str::password(8)],
            );

            $data = [$provider . '_id' => $providerResponse->getId()];

            if ($user->wasRecentlyCreated) {
                $data['name'] = $providerResponse->getName() ?? $providerResponse->getNickname();

                event(new Registered($user));
            }

            $user->update($data);

            Auth::login($user, remember: true);
        } catch (\Exception $e){
            logger('Error on social login: ' . $e->getMessage());
        }

        return redirect()->intended(route('home'));
    }
}
