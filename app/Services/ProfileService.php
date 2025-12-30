<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function getProfile(User $user)
    {
        return $user->load('profile');
    }

    public function updateProfile(User $user, array $data)
    {
        $user->update([
            'name'  => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        $profileData = collect($data)->only(['phone', 'NIK', 'address', 'city', 'province'])->toArray();

        if(!$user->profile){
            return $user->profile()->create($profileData);
        }

        $user->profile->update($profileData);

        return $user->fresh()->profile;
    }

    public function updatePassword(User $user, string $oldPassword, string $newPassword)
    {
        if (!password_verify($oldPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($newPassword)]);
        return true;
    }
}