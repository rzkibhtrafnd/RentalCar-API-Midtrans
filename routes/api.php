<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\CarController as AdminCarController;
use App\Http\Controllers\Api\User\CarController as UserCarController;
use App\Http\Controllers\Api\User\BookingController as UserBookingController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
        Route::apiResource('cars', AdminCarController::class);
        Route::get('/bookings', [AdminBookingController::class, 'index']);
        Route::patch('/bookings/{id}/finish', [AdminBookingController::class, 'finish']);
    });

    Route::apiResource('cars', UserCarController::class)->only(['index', 'show']);
    
    Route::get('/bookings', [UserBookingController::class, 'index']);
    Route::post('/cars/{car}/bookings', [UserBookingController::class, 'store']);
    Route::get('/cars/{car}/bookings/{booking}', [UserBookingController::class, 'show']);
    Route::patch('/cars/{car}/bookings/{booking}/cancel', [UserBookingController::class, 'cancel']);

    // PAYMENT
    Route::post('/cars/{car}/bookings/{booking}/payment', [PaymentController::class, 'createPayment']);
    
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

Route::post('/payments/webhook/midtrans', [WebhookController::class, 'handle']);