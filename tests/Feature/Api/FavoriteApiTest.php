<?php

use App\Models\Favorite;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
    $this->category = PlaceCategory::create(['name' => 'Test ' . uniqid(), 'slug' => 'test-' . uniqid(), 'is_active' => true]);
    $this->place = Place::factory()->create([
        'is_active' => true,
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
    ]);
});

test('authenticated user can add a place to favorites', function () {
    $this->actingAs($this->user)
        ->postJson('/api/favorites', ['place_id' => $this->place->id])
        ->assertCreated()
        ->assertJsonFragment(['place_id' => $this->place->id]);
});

test('cannot add same place to favorites twice', function () {
    Favorite::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->postJson('/api/favorites', ['place_id' => $this->place->id])
        ->assertStatus(409);
});

test('favorite validation requires place_id', function () {
    $this->actingAs($this->user)
        ->postJson('/api/favorites', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['place_id']);
});

test('favorite validation requires existing place', function () {
    $this->actingAs($this->user)
        ->postJson('/api/favorites', ['place_id' => 'non-existent-id'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['place_id']);
});

test('authenticated user can list their favorites', function () {
    Favorite::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->getJson('/api/favorites')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('authenticated user can remove a favorite', function () {
    Favorite::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->deleteJson("/api/favorites/{$this->place->slug}")
        ->assertNoContent();

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);
});

test('authenticated user can check if a place is favorited', function () {
    Favorite::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->getJson("/api/favorites/{$this->place->slug}/check")
        ->assertOk()
        ->assertJson(['is_favorited' => true]);
});

test('user cannot remove non-existent favorite', function () {
    $this->actingAs($this->user)
        ->deleteJson("/api/favorites/{$this->place->slug}")
        ->assertNotFound();
});
