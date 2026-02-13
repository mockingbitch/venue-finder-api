<?php

/**
 * Feature tests for auth API: login, register, logout (JWT).
 */

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can login and receive JWT token', function () {
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'expires_in', 'user' => ['id', 'name', 'email', 'role']]);
});

it('rejects invalid login', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrong',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('can register a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonPath('user.email', 'new@example.com')
        ->assertJsonPath('user.role', 'user');
    $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
});

it('registration cannot set role via request (mass assignment protection)', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ]);

    $response->assertCreated()
        ->assertJsonPath('user.role', 'user');
    $this->assertDatabaseHas('users', ['email' => 'hacker@example.com', 'role' => 'user']);
});

it('can logout with JWT', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/logout');

    $response->assertOk();
});

it('can refresh token', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/refresh');

    $response->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'expires_in', 'user']);
    $newToken = $response->json('token');
    $this->assertNotSame($token, $newToken);
});
