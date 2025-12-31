<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id'         => Booking::factory(),
            'order_id'           => 'ORDER-' . strtoupper($this->faker->bothify('###??')),
            'transaction_id'     => $this->faker->uuid(),
            'payment_type'       => 'qris',
            'transaction_status' => 'pending',
            'fraud_status'       => null,
            'gross_amount'       => $this->faker->randomFloat(2, 100000, 500000),
            'payload'            => null,
        ];
    }
}
