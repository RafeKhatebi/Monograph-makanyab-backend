<?php

use App\Models\Favorite;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
    $this->category = PlaceCategory::create(['name' => 'Test '.uniqid(), 'slug' => 'test-'.uniqid(), 'is_active' => true]);
    $this->place = Place::factory()->create([
        'is_active' => true,
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
    ]);
    $this->serviceCategory = ServiceCategory::create(['name' => 'Service '.uniqid(), 'slug' => 'service-'.uniqid(), 'is_active' => true]);
    $this->service = Service::factory()->create([
        'is_active' => true,
        'user_id' => $this->user->id,
        'service_category_id' => $this->serviceCategory->id,
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

test('database prevents duplicate place and service favorites', function () {
    Favorite::create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    expect(fn () => Favorite::create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]))->toThrow(QueryException::class);

    Favorite::create([
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
    ]);

    expect(fn () => Favorite::create([
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
    ]))->toThrow(QueryException::class);
});

test('favorite must target exactly one place or service', function () {
    expect(fn () => Favorite::create(['user_id' => $this->user->id]))
        ->toThrow(InvalidArgumentException::class);

    expect(fn () => Favorite::create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
        'service_id' => $this->service->id,
    ]))->toThrow(InvalidArgumentException::class);
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

test('favorites listing excludes inactive and deleted records', function () {
    $inactivePlace = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
        'is_active' => false,
    ]);
    $deletedPlace = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
        'is_active' => true,
    ]);
    $inactiveService = Service::factory()->create([
        'user_id' => $this->user->id,
        'service_category_id' => $this->serviceCategory->id,
        'is_active' => false,
    ]);

    Favorite::create(['user_id' => $this->user->id, 'place_id' => $this->place->id]);
    Favorite::create(['user_id' => $this->user->id, 'place_id' => $inactivePlace->id]);
    Favorite::create(['user_id' => $this->user->id, 'place_id' => $deletedPlace->id]);
    Favorite::create(['user_id' => $this->user->id, 'service_id' => $inactiveService->id]);
    $deletedPlace->delete();

    $this->actingAs($this->user)
        ->getJson('/api/favorites')
        ->assertOk()
        ->assertJsonCount(1, 'data');

    $this->actingAs($this->user)
        ->getJson('/api/service-favorites')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('favorites page shows empty state and paginates places and services independently', function () {
    $this->actingAs($this->user)
        ->get('/favorites')
        ->assertOk()
        ->assertSee('No Saved Places Yet');

    foreach (range(1, 13) as $number) {
        $place = Place::factory()->create([
            'user_id' => $this->user->id,
            'place_category_id' => $this->category->id,
            'name' => "Saved Place {$number}",
            'is_active' => true,
        ]);
        Favorite::create(['user_id' => $this->user->id, 'place_id' => $place->id]);

        $service = Service::factory()->create([
            'user_id' => $this->user->id,
            'service_category_id' => $this->serviceCategory->id,
            'name' => "Saved Service {$number}",
            'is_active' => true,
        ]);
        Favorite::create(['user_id' => $this->user->id, 'service_id' => $service->id]);
    }

    $this->actingAs($this->user)
        ->get('/favorites')
        ->assertOk()
        ->assertSee('places')
        ->assertSee('Saved services')
        ->assertSee('page=2', false)
        ->assertSee('services_page=2', false);
});

test('logged out users cannot manage favorites', function () {
    $this->postJson('/api/favorites', ['place_id' => $this->place->id])
        ->assertUnauthorized();

    $this->get('/favorites')
        ->assertRedirect('/login');
});

test('inactive or deleted records cannot be added to favorites', function () {
    $inactivePlace = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
        'is_active' => false,
    ]);
    $deletedPlace = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
        'is_active' => true,
    ]);
    $deletedPlace->delete();
    $inactiveService = Service::factory()->create([
        'user_id' => $this->user->id,
        'service_category_id' => $this->serviceCategory->id,
        'is_active' => false,
    ]);

    $this->actingAs($this->user)
        ->postJson('/api/favorites', ['place_id' => $inactivePlace->id])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['place_id']);

    $this->actingAs($this->user)
        ->postJson('/api/favorites', ['place_id' => $deletedPlace->id])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['place_id']);

    $this->actingAs($this->user)
        ->postJson('/api/service-favorites/'.$inactiveService->slug)
        ->assertNotFound();
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

test('service favorite duplicate attempts return conflict', function () {
    Favorite::create([
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
    ]);

    $this->actingAs($this->user)
        ->postJson('/api/service-favorites/'.$this->service->slug)
        ->assertStatus(409)
        ->assertJson(['message' => 'Already in favorites.']);
});
