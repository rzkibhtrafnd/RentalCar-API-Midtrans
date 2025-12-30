<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->auth->register($request->validated());
        return ApiResponse::success('Register berhasil', $user, 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->auth->login($request->validated());

        if (!$user) {
            return ApiResponse::error('Email atau password salah', 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return ApiResponse::success('Login berhasil', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());
        return ApiResponse::success('Logout berhasil', null);
    }
}
