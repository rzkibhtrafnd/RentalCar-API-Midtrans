<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\CarController as AdminCarController;
use App\Http\Controllers\Api\User\CarController as UserCarController;
use App\Http\Controllers\Api\User\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::apiResource('cars', AdminCarController::class);
    });

    Route::apiResource('cars', UserCarController::class)->only(['index', 'show']);
    
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/cars/{car}/bookings', [BookingController::class, 'store']);
    Route::get('/cars/{car}/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/cars/{car}/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // PAYMENT
    Route::post('/cars/{car}/bookings/{booking}/payment', [PaymentController::class, 'createPayment']);
    
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

Route::post('/payments/webhook/midtrans', [WebhookController::class, 'handle']);