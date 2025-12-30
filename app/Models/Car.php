<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'seat_count',
        'transmission',
        'price_per_day',
        'available_status',
        'image',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'image' => 'array'
    ];

    // Filter Transmission
    public function scopeTransmission($query, $transmission)
    {
        if ($transmission) {
            return $query->where('transmission', $transmission);
        }
        return $query;
    }

    // Filter Status Ketersediaan
    public function scopeAvailableStatus($query, $status)
    {
        if ($status) {
            return $query->where('available_status', $status);
        }
        return $query;
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
