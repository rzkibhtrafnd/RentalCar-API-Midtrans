<?php

namespace Tests\Feature\User;

use App\Models\Car;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
        return $user;
    }

    /** @test */
    public function user_can_view_car_list()
    {
        $this->actingAsUser();

        Car::factory()->count(3)->create();

        $response = $this->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Daftar mobil berhasil diambil',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'plate_number', 'seat_count', 'transmission', 'price_per_day']
                ]
            ]);
    }

    /** @test */
    public function user_can_filter_cars_based_on_transmission()
    {
        $this->actingAsUser();

        Car::factory()->create(['transmission' => 'manual']);
        Car::factory()->create(['transmission' => 'auto']);

        $response = $this->getJson('/api/cars?transmission=manual');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function user_can_view_car_details()
    {
        $this->actingAsUser();

        $car = Car::factory()->create();

        $response = $this->getJson("/api/cars/{$car->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Detail mobil berhasil diambil'
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'price_per_day']
            ]);
    }
}
