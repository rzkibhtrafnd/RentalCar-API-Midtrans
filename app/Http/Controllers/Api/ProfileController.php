<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use GrahamCampbell\ResultType\Success;
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
        $profile = $this->service->getProfile($request->user());
        return ApiResponse::success('Data profil berhasil diambil', $profile, 201);
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = $this->service->updateProfile($request->user(), $request->validated());
        return ApiResponse::success('Profile berhasil diperbarui', $profile, 201);
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

        return ApiResponse::success('Password berhasil diperbarui', 201);
    }
}
