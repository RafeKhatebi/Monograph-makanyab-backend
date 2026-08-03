<?php

use App\Models\Favorite;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $category = ServiceCategory::create([
        'name' => 'Repairs',
        'slug' => 'repairs',
        'is_active' => true,
    ]);
    $this->service = Service::create([
        'user_id' => $this->user->id,
        'service_category_id' => $category->id,
        'name' => 'Repair Service',
        'slug' => 'repair-service',
        'description' => 'Repairs.',
        'phone_1' => '+93000000000',
        'address' => 'Main Street',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Kabul',
        'status' => 'open',
        'price_level' => 'medium',
        'is_active' => true,
    ]);
});

test('user can add check list and remove a service favorite', function () {
    $this->actingAs($this->user)
        ->postJson('/api/service-favorites/'.$this->service->slug)
        ->assertCreated();

    $this->actingAs($this->user)
        ->getJson('/api/service-favorites/'.$this->service->slug.'/check')
        ->assertOk()
        ->assertJson(['is_favorited' => true]);

    $this->actingAs($this->user)
        ->getJson('/api/service-favorites')
        ->assertOk()
        ->assertJsonCount(1, 'data');

    $this->actingAs($this->user)
        ->get('/favorites')
        ->assertOk()
        ->assertSee('Repair Service');

    $this->actingAs($this->user)
        ->deleteJson('/api/service-favorites/'.$this->service->slug)
        ->assertNoContent();
});

test('service favorites are isolated by user', function () {
    Favorite::create(['user_id' => $this->user->id, 'service_id' => $this->service->id]);
    $other = User::factory()->create();

    $this->actingAs($other)
        ->deleteJson('/api/service-favorites/'.$this->service->slug)
        ->assertNotFound();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
    ]);
});

test('user can create edit and delete one service review', function () {
    $reviewId = $this->actingAs($this->user)
        ->postJson('/api/services/'.$this->service->slug.'/reviews', [
            'rating' => 5,
            'comment' => 'Excellent.',
        ])
        ->assertCreated()
        ->json('id');

    $this->actingAs($this->user)
        ->postJson('/api/services/'.$this->service->slug.'/reviews', ['rating' => 4])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('service_id');

    $this->actingAs($this->user)
        ->patchJson('/api/services/'.$this->service->slug.'/reviews/'.$reviewId, [
            'rating' => 4,
            'comment' => 'Updated.',
        ])
        ->assertOk()
        ->assertJsonFragment(['rating' => 4, 'is_approved' => false]);

    $this->actingAs($this->user)
        ->deleteJson('/api/services/'.$this->service->slug.'/reviews/'.$reviewId)
        ->assertNoContent();
});

test('non-owner cannot edit a service review', function () {
    $review = Review::create([
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
        'rating' => 5,
    ]);

    $this->actingAs(User::factory()->create())
        ->patchJson('/api/services/'.$this->service->slug.'/reviews/'.$review->id, [
            'rating' => 1,
        ])
        ->assertForbidden();
});

test('service average and rating filter use approved reviews only', function () {
    Review::create([
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
        'rating' => 2,
        'is_approved' => true,
    ]);
    Review::create([
        'user_id' => User::factory()->create()->id,
        'service_id' => $this->service->id,
        'rating' => 5,
        'is_approved' => false,
    ]);

    expect($this->service->fresh()->avg_rating)->toBe(2.0)
        ->and(Service::query()->filterRatingAtLeast(3)->whereKey($this->service->id)->exists())->toBeFalse();
});
