<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    // Function User

    public function createBooking(int $carId, array $data): Booking
    {
        $car = Car::findOrFail($carId);

        $start = new \Carbon\Carbon($data['start_date']);
        $end   = new \Carbon\Carbon($data['end_date']);
        $duration = $start->diffInDays($end) + 1;

        $totalPrice = $duration * $car->price_per_day;

        $hasBooking = Booking::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'paid']) // mobil sedang dipesan
            ->where(function ($query) use ($start, $end) {
                $query
                    ->where('start_date', '<=', $end)
                    ->where('end_date', '>=', $start);
            })
            ->exists();

        if ($hasBooking) {
            throw new \Exception('Mobil sudah dipesan pada tanggal yang dipilih.');
        }

        return Booking::create([
            'user_id'        => Auth::id(),
            'car_id'         => $car->id,
            'start_date'     => $data['start_date'],
            'end_date'       => $data['end_date'],
            'duration_days'  => $duration,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function getAllForUser(int $userId)
    {
        return Booking::where('user_id', $userId)
            ->with(['car', 'payment'])
            ->orderByDesc('created_at')
            ->get();
    }

    // Function Admin

    public function getAllBookings()
    {
        return Booking::with(['user', 'car', 'payment'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateStatusToFinished(int $bookingId): ?Booking
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return null;
        }

        $booking->status = 'finished';
        $booking->save();

        return $booking;
    }
}