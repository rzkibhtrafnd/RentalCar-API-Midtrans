<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function createPayment($carId, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('car_id', $carId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return ApiResponse::error('Pemesanan tidak ditemukan', 404);
        }

        if ($booking->payment_status === 'paid') {
            return ApiResponse::error('Pemesanan ini sudah dibayar.');
        }

        $token = $this->service->createMidtransPayment($booking);

        return ApiResponse::success('Payment token generated', [
            'snap_token' => $token,
            'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/$token"
        ]);
    }
}
