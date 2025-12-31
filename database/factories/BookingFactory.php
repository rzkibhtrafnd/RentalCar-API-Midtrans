<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $start = now()->addDays(1);
        $end = now()->addDays(3);
        $duration = $start->diffInDays($end);

        return [
            'user_id'        => User::factory(),
            'car_id'         => Car::factory(),
            'start_date'     => $start->toDateString(),
            'end_date'       => $end->toDateString(),
            'duration_days'  => $duration,
            'total_price'    => 300000 * $duration,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ];
    }
}
