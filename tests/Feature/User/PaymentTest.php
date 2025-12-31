<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_payment_token()
    {
        $this->mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createMidtransPayment')->andReturn('TEST_TOKEN_123');
        });

        $user = User::factory()->create();
        $car  = Car::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'car_id'  => $car->id,
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($user, 'sanctum')
           ->postJson("/api/cars/{$car->id}/bookings/{$booking->id}/payment");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['snap_token', 'redirect_url']
            ]);
    }

    public function test_user_cannot_generate_token_twice_if_already_paid()
    {
        $user = User::factory()->create();
        $car  = Car::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'car_id'  => $car->id,
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/cars/{$car->id}/bookings/{$booking->id}/payment");

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }
}
