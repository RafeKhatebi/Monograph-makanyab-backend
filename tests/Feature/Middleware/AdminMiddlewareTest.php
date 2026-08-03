<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect('/login');
});

test('regular user gets 403 on admin routes', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('owner user gets 403 on admin routes', function () {
    $user = User::factory()->create(['role' => 'owner']);

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('admin user can access admin routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk();
});

test('admin can access places management', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/places')
        ->assertOk();
});

test('admin can access users management', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk();
});

test('admin can access reviews management', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/reviews')
        ->assertOk();
});

test('regular user cannot access admin places', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/admin/places')
        ->assertForbidden();
});

test('regular user cannot access admin users', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertForbidden();
});
