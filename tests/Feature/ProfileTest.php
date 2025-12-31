<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_retrieve_profile_data()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/profile');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Data profil berhasil diambil',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'id', 'name', 'email', 
                         'profile' => ['address', 'phone', 'NIK', 'city', 'province']
                     ]
                 ]);
    }

    /** @test */
    public function user_can_update_profile()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $token = $user->createToken('api')->plainTextToken;

        $payload = [
            'name'    => 'Updated Name',
            'address' => 'Alamat Baru',
            'phone'   => '089123456789'
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->putJson('/api/profile', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Profile berhasil diperbarui'
                 ]);

        // assert user updated
        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);

        // assert profile updated
        $this->assertDatabaseHas('profiles', [
            'address' => 'Alamat Baru',
            'phone'   => '089123456789',
        ]);
    }

    /** @test */
    public function update_password_failed_if_old_password_is_incorrect()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $token = $user->createToken('api')->plainTextToken;

        $payload = [
            'old_password'      => 'salah_password',
            'new_password'      => 'passwordbaru123',
            'confirm_password'  => 'passwordbaru123'
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->putJson('/api/profile/password', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Kata sandi lama salah'
                 ]);
    }

    /** @test */
    public function user_update_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $token = $user->createToken('api')->plainTextToken;

        $payload = [
            'old_password'      => 'password123',
            'new_password'      => 'passwordbaru123',
            'confirm_password'  => 'passwordbaru123'
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->putJson('/api/profile/password', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Password berhasil diperbarui'
                 ]);

        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
    }
}
