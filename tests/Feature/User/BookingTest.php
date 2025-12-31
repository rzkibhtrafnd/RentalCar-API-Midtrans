<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /** @test */
    public function user_can_list_bookings()
    {
        $user = $this->actingAsUser();
        Booking::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Daftar pemesanan diambil'
                 ]);

        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function user_can_create_booking()
    {
        $user = $this->actingAsUser();
        $car = Car::factory()->create();

        $payload = [
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date'   => now()->addDays(3)->format('Y-m-d'),
            'duration_days' => 2,
            'total_price' => 600000
        ];

        $response = $this->postJson("/api/cars/{$car->id}/bookings", $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Booking berhasil dibuat'
                 ]);

        $this->assertDatabaseHas('bookings', [
            'car_id' => $car->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_can_view_own_booking_detail()
    {
        $user = $this->actingAsUser();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/cars/{$booking->car_id}/bookings/{$booking->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Detail pemesanan diambil'
                 ]);
    }

    /** @test */
    public function user_can_cancel_pending_booking()
    {
        $user = $this->actingAsUser();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->patchJson("/api/cars/{$booking->car_id}/bookings/{$booking->id}/cancel");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Pemesanan dibatalkan'
                 ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }
}
