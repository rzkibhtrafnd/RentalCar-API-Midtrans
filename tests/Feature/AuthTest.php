<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user_baru()
    {
        $payload = [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Register berhasil',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function login_gagal_jika_email_salah()
    {
        $payload = [
            'email'    => 'wrong@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Email atau password salah',
                 ]);
    }

    /** @test */
    public function dapat_login_user_exist_dan_mendapatkan_token()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
        ]);

        $payload = [
            'email' => 'login@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Login berhasil',
                ])
                ->assertJsonStructure([
                    'data' => [
                        'token',
                        'user' => [
                            'id', 'name', 'email'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function logout_dan_token_dihapus()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        // bawa token saat logout
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout berhasil'
                 ]);

        // memastikan token revoked
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
