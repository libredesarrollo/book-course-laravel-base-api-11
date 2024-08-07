<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/user/login', $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'isLoggedIn',
            'token',
            'user',
        ]);
    }
    public function test_login_incorrect(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $data = [
            'email' => $user->email,
            'password' => 'invalid-password',
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/user/login', $data);

        $response->assertStatus(422);

    }
    public function test_logout(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/user/login', $data);

        // dd(User::first()->tokens);
        // $token = User::first()->tokens[0]->token;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            // 'Authorization' => 'Bearer '.$token 
        ])->post('/api/user/logout');

        $response->assertStatus(200);
        $this->assertEquals(count(User::first()->tokens), 0);

        // $token = User::first()->tokens[0];

    }
    public function test_logout2(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post('/api/user/logout');

        $response->assertStatus(200);
        $this->assertEquals(count(User::first()->tokens), 0);

        // $token = User::first()->tokens[0];

    }
    public function test_check_token(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post('/api/user/token-check', ['token' => $token]);

        $response->assertStatus(200);

        $response->assertJson([
            'isLoggedIn' => true,
            'token' => $token
        ]);

    }
    public function test_check_invalid_token(): void
    {
        // create user factory
        User::factory()->create();
        $user = User::first();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/token-check', ['token' => 'tokeninvalido']);

        $response->assertStatus(422)->assertContent('"Invalid user"');

    }
}
