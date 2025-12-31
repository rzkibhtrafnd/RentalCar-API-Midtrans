<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id'  => User::factory(),
            'phone'    => $this->faker->phoneNumber(),
            'NIK'      => $this->faker->numerify('################'),
            'address'  => $this->faker->address(),
            'city'     => $this->faker->city(),
            'province' => $this->faker->state(),
        ];
    }
}
