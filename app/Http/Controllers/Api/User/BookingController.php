<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Responses\ApiResponse;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class BookingController extends Controller
{
    private BookingService $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $bookings = $this->service->getAllForUser(Auth::id());

        return ApiResponse::success('Daftar pemesanan diambil', $bookings);
    }

    public function store(CreateBookingRequest $request, $carId)
{
    try {
        $booking = $this->service->createBooking($carId, $request->validated());
        return ApiResponse::success('Booking dibuat', $booking);

    } catch (\Exception $e) {
        return ApiResponse::error($e->getMessage(), 422);
    }
}

    public function show($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['car', 'payment'])
            ->first();

        if (!$booking) {
            return ApiResponse::error('Pemesanan tidak ditemukan', 404);
        }

        return ApiResponse::success('Detail pemesanan diambil', $booking);
    }

    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return ApiResponse::error('Pemesanan tidak ada', 404);
        }

        if ($booking->status !== 'pending') {
            return ApiResponse::error('Hanya pemesanan yang masih dalam proses yang dapat dibatalkan.', 422);
        }

        $booking->update([
            'status'         => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return ApiResponse::success('Pemesanan dibatalkan');
    }
}
