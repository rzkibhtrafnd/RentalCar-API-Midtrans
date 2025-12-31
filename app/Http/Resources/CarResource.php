<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'plate_number'     => $this->plate_number,
            'seat_count'       => $this->seat_count,
            'transmission'     => $this->transmission,
            'price_per_day'    => $this->price_per_day,
            'available_status' => $this->available_status,
            'image'            => $this->image,
            'created_at'       => $this->created_at->toDateTimeString(),
            'updated_at'       => $this->updated_at->toDateTimeString(),
        ];
    }
}
