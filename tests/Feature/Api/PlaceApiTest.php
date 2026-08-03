<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
    $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $this->category = PlaceCategory::create([
        'name' => 'Test Category ' . uniqid(),
        'slug' => 'test-category-' . uniqid(),
        'is_active' => true,
    ]);
});

test('unauthenticated user cannot access protected places endpoints', function () {
    $this->postJson('/api/places', ['name' => 'Test'])
        ->assertStatus(401);
});

test('authenticated user can list places', function () {
    Place::factory()->count(3)->create(['is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->user)
        ->getJson('/api/places')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('authenticated user can view a place', function () {
    $place = Place::factory()->create(['is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->user)
        ->getJson("/api/places/{$place->slug}")
        ->assertOk()
        ->assertJsonFragment(['id' => $place->id]);
});

test('admin can create a place', function () {
    $data = [
        'name' => 'New Place',
        'place_category_id' => $this->category->id,
        'description' => 'A test place',
        'address' => '123 Test Street',
        'phone_1' => '+1234567890',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/places', $data)
        ->assertCreated()
        ->assertJsonFragment(['name' => 'New Place']);
});

test('regular user cannot create a place', function () {
    $data = [
        'name' => 'New Place',
        'place_category_id' => $this->category->id,
        'description' => 'A test place',
        'address' => '123 Test Street',
        'phone_1' => '+1234567890',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/places', $data)
        ->assertForbidden();
});

test('place owner can update their place', function () {
    $place = Place::factory()->create(['user_id' => $this->user->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->user)
        ->putJson("/api/places/{$place->slug}", ['name' => 'Updated Name'])
        ->assertOk()
        ->assertJsonFragment(['name' => 'Updated Name']);
});

test('non-owner cannot update place', function () {
    $otherUser = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['user_id' => $otherUser->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->user)
        ->putJson("/api/places/{$place->slug}", ['name' => 'Hacked'])
        ->assertForbidden();
});

test('place owner can delete their place', function () {
    $place = Place::factory()->create(['user_id' => $this->user->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->user)
        ->deleteJson("/api/places/{$place->slug}")
        ->assertNoContent();

    $this->assertSoftDeleted('places', ['id' => $place->id]);
});

test('places can be filtered by search term', function () {
    Place::factory()->create(['name' => 'Kabul Restaurant', 'is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id, 'city' => 'Kabul']);
    Place::factory()->create(['name' => 'Kabul Hotel', 'is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id, 'city' => 'Kabul']);
    Place::factory()->create(['name' => 'Mazar Shop', 'is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id, 'city' => 'Mazar']);

    $response = $this->actingAs($this->user)
        ->getJson('/api/places?search=Kabul')
        ->assertOk();

    $this->assertCount(2, $response->json('data'));
});

test('places can be filtered by category', function () {
    $otherCategory = PlaceCategory::create(['name' => 'Other ' . uniqid(), 'slug' => 'other-' . uniqid(), 'is_active' => true]);
    Place::factory()->create(['place_category_id' => $this->category->id, 'is_active' => true, 'user_id' => $this->user->id]);
    Place::factory()->create(['place_category_id' => $otherCategory->id, 'is_active' => true, 'user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)
        ->getJson("/api/places?category={$this->category->slug}")
        ->assertOk();

    $this->assertCount(1, $response->json('data'));
});

test('places pagination returns data', function () {
    Place::factory()->count(5)->create(['is_active' => true, 'user_id' => $this->user->id, 'place_category_id' => $this->category->id]);

    $response = $this->actingAs($this->user)
        ->getJson('/api/places?per_page=3')
        ->assertOk();

    $this->assertArrayHasKey('data', $response->json());
    $this->assertArrayHasKey('links', $response->json());
});
