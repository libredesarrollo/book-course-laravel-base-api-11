<?php

use App\Models\User;

test('test login', function () {
    // create user factory
    User::factory()->create();
    $user = User::first();

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $this->post('/api/user/login', $data, [
        'Accept' => 'application/json'
    ])->assertStatus(200)
        ->assertJsonStructure([
            'isLoggedIn',
            'token',
            'user',
        ]);
});

test('test login incorrect', function () {
    User::factory()->create();
    $user = User::first();

    $data = [
        'email' => $user->email,
        'password' => 'invalid-password',
    ];

    $response = $this->post('/api/user/login', $data, [
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(422);
});

test('test logout', function () {
    User::factory()->create();
    $user = User::first();

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $this->post('/api/user/login', $data, [
        'Accept' => 'application/json'
    ]);

    $this->post('/api/user/logout', [], [
        'Accept' => 'application/json'
    ])->assertStatus(200);
    $this->assertEquals(count(User::first()->tokens), 0);
});

test('test logout2', function () {
    User::factory()->create();
    $user = User::first();

    $token = $user->createToken('myapptoken')->plainTextToken;
    $this->post('/api/user/logout', [], [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(200);

    $this->assertEquals(count(User::first()->tokens), 0);
});

test('test check token', function () {
    // create user factory
    User::factory()->create();
    $user = User::first();

    $token = $user->createToken('myapptoken')->plainTextToken;
    $this->post('/api/user/token-check', ['token' => $token], [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ])->assertStatus(200)->assertJson([
                'isLoggedIn' => true,
                'token' => $token
            ]);
});

test('test check invalid token', function () {
    // create user factory
    User::factory()->create();
    $user = User::first();

    $this->post('/api/user/token-check', ['token' => 'tokeninvalido'], [
        'Accept' => 'application/json',
    ])->assertStatus(422)->assertContent('"Invalid user"');
});

