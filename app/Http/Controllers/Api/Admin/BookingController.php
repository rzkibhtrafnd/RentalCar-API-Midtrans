<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\BookingService;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private BookingService $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $bookings = $this->service->getAllBookings();
        return ApiResponse::success('Daftar semua booking', BookingResource::collection($bookings), 200);
    }

    public function finish($id)
    {
        $booking = $this->service->updateStatusToFinished($id);

        if (!$booking) {
            return ApiResponse::error('Booking tidak ditemukan', 404);
        }

        return ApiResponse::success('Status booking berhasil diubah menjadi finished', new BookingResource($booking), 200);
    }
}
