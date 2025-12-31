<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Car;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CarTest extends TestCase
{
    protected function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);
        return $admin;
    }

    public function test_admin_can_list_cars()
    {
        $this->actingAsAdmin();
        Car::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/cars');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Daftar mobil berhasil diambil'
                 ]);
    }

    public function test_admin_can_store_car()
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => 'Toyota Avanza',
            'plate_number' => 'B-1234-XYZ',
            'seat_count' => 7,
            'transmission' => 'auto',
            'price_per_day' => 350000,
            'available_status' => 'ready'
        ];

        $response = $this->postJson('/api/admin/cars', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Mobil berhasil ditambahkan'
                 ]);

        $this->assertDatabaseHas('cars', ['name' => 'Toyota Avanza']);
    }

    public function test_admin_can_show_car_detail()
    {
        $this->actingAsAdmin();
        $car = Car::factory()->create();

        $response = $this->getJson("/api/admin/cars/{$car->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Detail mobil berhasil diambil'
                 ]);
    }

    public function test_admin_can_update_car()
    {
        $this->actingAsAdmin();
        $car = Car::factory()->create();

        $payload = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/admin/cars/{$car->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Mobil berhasil diperbarui'
                 ]);

        $this->assertDatabaseHas('cars', ['name' => 'Updated Name']);
    }

    public function test_admin_can_delete_car()
    {
        $this->actingAsAdmin();
        $car = Car::factory()->create();

        $response = $this->deleteJson("/api/admin/cars/{$car->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Mobil berhasil dihapus'
                 ]);

        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }
}
