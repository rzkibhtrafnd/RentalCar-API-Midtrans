<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createMidtransPayment(Booking $booking)
    {
        $orderId = 'ORDER-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'      => $orderId,
                'gross_amount'  => $booking->total_price,
            ],
            'customer_details' => [
                'first_name'    => $booking->user->name,
                'email'         => $booking->user->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        Payment::create([
            'booking_id'        => $booking->id,
            'order_id'          => $orderId,
            'gross_amount'      => $booking->total_price,
            'transaction_status'=> 'pending',
        ]);

        return $snapToken;
    }
}