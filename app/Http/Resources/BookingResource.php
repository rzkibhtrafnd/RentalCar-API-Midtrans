<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user'          => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'car'           => $this->whenLoaded('car', function () {
                return [
                    'id'           => $this->car->id,
                    'name'         => $this->car->name,
                    'plate_number' => $this->car->plate_number,
                    'price_per_day'=> $this->car->price_per_day,
                    'available_status'=> $this->car->available_status,
                ];
            }),
            'payment'       => $this->whenLoaded('payment', function () {
                return [
                    'id'                 => $this->payment->id,
                    'transaction_status' => $this->payment->transaction_status,
                    'gross_amount'       => $this->payment->gross_amount,
                ];
            }),
            'start_date'    => $this->start_date->toDateString(),
            'end_date'      => $this->end_date->toDateString(),
            'duration_days' => $this->duration_days,
            'total_price'   => $this->total_price,
            'status'        => $this->status,
            'payment_status'=> $this->payment_status,
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }
}
