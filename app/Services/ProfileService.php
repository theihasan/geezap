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
    public function updatePersonalInfo(PersonalInfoUpdateRequest $request, $user): void
    {
        $data = $request->validated();
        $user->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'dob' => $data['dob'],
            'state' => $data['state'],
            'country' => $data['country'],
            'occupation' => $data['occupation'],
            'timezone' => $data['timezone'],
            'phone' => $data['phone'],
            'bio' => $data['bio'],
        ]);
    }

    public function updateContactInfo(ContactInfoUpdateRequest $request, $user): void
    {
        $data = $request->validated();
        $user->update([
            'phone' => $data['phone'],
            'website' => $data['website'],
        ]);
    }

    public function updatePassword(PasswordUpdateRequest $request, $user)
    {
        $data = $request->validated();
        if(!Hash::check($data['current_password'], $user->password)) {
            return Redirect::route('profile.update')->with('status', 'Current password is incorrect');
        }
        $user->update([
            'password' => bcrypt($data['password']),
        ]);
    }

    public function updateSocialMediaInfo(SocialMediaInfoUpdateRequest $request, $user): void
    {
        $data = $request->validated();
        $user->update([
            'facebook' => $data['facebook'],
            'twitter' => $data['twitter'],
            'linkedin' => $data['linkedin'],
            'github' => $data['github'],
        ]);
    }

}
