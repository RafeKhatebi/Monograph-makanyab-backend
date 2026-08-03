<?php

use App\Models\PlaceCategory;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
    $this->placeCategory = PlaceCategory::create([
        'name' => 'Test Place Category',
        'slug' => 'test-place-category',
        'is_active' => true,
    ]);
    $this->serviceCategory = ServiceCategory::create([
        'name' => 'Test Service Category',
        'slug' => 'test-service-category',
        'is_active' => true,
    ]);
});

test('authenticated user can submit a place suggestion', function () {
    $data = [
        'name' => 'Suggested Place',
        'place_category_id' => $this->placeCategory->id,
        'description' => 'A great place',
        'address' => '123 Suggestion Street',
        'phone_1' => '+1234567890',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
        'price_level' => 'medium',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/suggestions/place', $data)
        ->assertCreated()
        ->assertJsonFragment(['name' => 'Suggested Place']);
});

test('place suggestion validation requires name', function () {
    $this->actingAs($this->user)
        ->postJson('/api/suggestions/place', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('place suggestion validation requires price_level', function () {
    $this->actingAs($this->user)
        ->postJson('/api/suggestions/place', [
            'name' => 'Test',
            'price_level' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['price_level']);
});

test('authenticated user can submit a service suggestion', function () {
    $data = [
        'name' => 'Suggested Service',
        'service_category_id' => $this->serviceCategory->id,
        'description' => 'A great service',
        'address' => '123 Service Street',
        'phone_1' => '+1234567890',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
        'price_level' => 'low',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/suggestions/service', $data)
        ->assertCreated()
        ->assertJsonFragment(['name' => 'Suggested Service']);
});

test('authenticated user can view their place suggestions', function () {
    $this->actingAs($this->user)
        ->getJson('/api/my-suggestions/places')
        ->assertOk();
});

test('authenticated user can view their service suggestions', function () {
    $this->actingAs($this->user)
        ->getJson('/api/my-suggestions/services')
        ->assertOk();
});

test('admin can view place suggestion queue', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->getJson('/api/admin/suggestions/places')
        ->assertOk();
});

test('admin can view service suggestion queue', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->getJson('/api/admin/suggestions/services')
        ->assertOk();
});

test('regular user cannot access admin suggestion queues', function () {
    $this->actingAs($this->user)
        ->getJson('/api/admin/suggestions/places')
        ->assertForbidden();
});
