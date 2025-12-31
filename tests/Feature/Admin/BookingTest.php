<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Booking;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);
        return $admin;
    }

    /** @test */
    public function admin_can_list_all_bookings()
    {
        $this->actingAsAdmin();
        Booking::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/bookings');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Daftar semua booking'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function admin_can_finish_booking()
    {
        $this->actingAsAdmin();
        $booking = Booking::factory()->create(['status' => 'pending']);

        $response = $this->patchJson("/api/admin/bookings/{$booking->id}/finish");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Status booking berhasil diubah menjadi finished'
                 ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'finished'
        ]);
    }
}
