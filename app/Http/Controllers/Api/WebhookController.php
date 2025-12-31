<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info("MIDTRANS WEBHOOK:", $payload);

        $payment = Payment::where('order_id', $payload['order_id'] ?? null)->first();

        if (!$payment) {
            return ApiResponse::error('Payment not found', 404);
        }

        $payment->update([
            'transaction_id'     => $payload['transaction_id'] ?? null,
            'payment_type'       => $payload['payment_type'] ?? null,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'fraud_status'       => $payload['fraud_status'] ?? null,
            'payload'            => json_encode($payload),
        ]);

        $transactionStatus = $payload['transaction_status'] ?? null;

        match ($transactionStatus) {
            'settlement', 'capture' =>
                Booking::where('id', $payment->booking_id)->update([
                    'payment_status' => 'paid',
                    'status' => 'paid'
                ]),

            'cancel', 'deny', 'expire' =>
                Booking::where('id', $payment->booking_id)->update([
                    'payment_status' => 'cancelled',
                    'status' => 'cancelled'
                ]),

            default => null
        };

        return ApiResponse::success('Webhook processed', $payment->refresh());
    }
}
