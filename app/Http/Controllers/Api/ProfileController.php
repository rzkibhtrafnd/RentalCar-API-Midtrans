<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        $user = $request->user()->load('profile');
        return ApiResponse::success('Data profil berhasil diambil', new UserResource($user), 200);
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = $this->service->updateProfile($request->user(), $request->validated());
        $user = $request->user()->load('profile');

        return ApiResponse::success('Profile berhasil diperbarui', new UserResource($user), 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $success = $this->service->updatePassword(
            $request->user(),
            $request->old_password,
            $request->new_password,
        );

        if(!$success){
            return ApiResponse::error('Kata sandi lama salah', 401);
        }

        return ApiResponse::success('Password berhasil diperbarui', null, 200);
    }
}
