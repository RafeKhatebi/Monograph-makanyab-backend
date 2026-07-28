<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

test('user can register via API', function () {
    $data = [
        'name' => 'Test User',
        'username' => 'testuser_' . uniqid(),
        'email' => 'test_' . uniqid() . '@example.com',
        'password' => 'StrongPass1!',
        'password_confirmation' => 'StrongPass1!',
    ];

    $this->postJson('/api/auth/register', $data)
        ->assertCreated()
        ->assertJsonStructure(['token', 'token_type', 'user']);
});

test('registration validates required fields', function () {
    $this->postJson('/api/auth/register', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'username', 'email', 'password']);
});

test('registration validates unique username', function () {
    User::factory()->create(['username' => 'existinguser']);

    $this->postJson('/api/auth/register', [
        'name' => 'Test',
        'username' => 'existinguser',
        'email' => 'test@example.com',
        'password' => 'StrongPass1!',
        'password_confirmation' => 'StrongPass1!',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['username']);
});

test('registration validates unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $this->postJson('/api/auth/register', [
        'name' => 'Test',
        'username' => 'newuser',
        'email' => 'existing@example.com',
        'password' => 'StrongPass1!',
        'password_confirmation' => 'StrongPass1!',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('user can login with email', function () {
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => Hash::make('password123'),
        'is_active' => true,
    ]);

    $this->postJson('/api/auth/login', [
        'email' => 'login@example.com',
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'user']);
});

test('user can login with username', function () {
    $user = User::factory()->create([
        'username' => 'loginuser',
        'password' => Hash::make('password123'),
        'is_active' => true,
    ]);

    $this->postJson('/api/auth/login', [
        'email' => 'loginuser',
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'user']);
});

test('login fails with invalid credentials', function () {
    $this->postJson('/api/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword',
    ])
        ->assertUnprocessable()
        ->assertJson(['message' => 'Invalid credentials.']);
});

test('inactive user cannot login', function () {
    User::factory()->create([
        'email' => 'inactive@example.com',
        'password' => Hash::make('password123'),
        'is_active' => false,
    ]);

    $this->postJson('/api/auth/login', [
        'email' => 'inactive@example.com',
        'password' => 'password123',
    ])
        ->assertForbidden()
        ->assertJson(['message' => 'Account is inactive.']);
});

test('authenticated user can get current user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/auth/me')
        ->assertOk()
        ->assertJson(['email' => $user->email]);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/auth/logout')
        ->assertOk()
        ->assertJson(['message' => 'Logged out successfully.']);
});
