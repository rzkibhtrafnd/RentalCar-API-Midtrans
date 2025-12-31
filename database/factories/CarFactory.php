<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->company() . ' Car',
            'plate_number'    => strtoupper($this->faker->bothify('B-####-??')),
            'seat_count'      => $this->faker->randomElement([2, 4, 5, 7, 8]),
            'transmission'    => $this->faker->randomElement(['manual', 'auto']),
            'price_per_day'   => $this->faker->randomFloat(2, 150000, 1000000),
            'available_status'    => $this->faker->randomElement(['ready', 'booked', 'maintenance']),
            'image'           => [
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
            ]
        ];
    }
}
