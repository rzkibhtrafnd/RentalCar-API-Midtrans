<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'booking_id'         => $this->booking_id,
            'order_id'           => $this->order_id,
            'transaction_id'     => $this->transaction_id,
            'payment_type'       => $this->payment_type,
            'transaction_status' => $this->transaction_status,
            'fraud_status'       => $this->fraud_status,
            'gross_amount'       => $this->gross_amount,
            'payload'            => $this->payload,
            'booking'            => $this->whenLoaded('booking', function () {
                return $this->booking ? new BookingResource($this->booking) : null;
            }),
            'created_at'         => $this->created_at->toDateTimeString(),
            'updated_at'         => $this->updated_at->toDateTimeString(),
        ];
    }
}
