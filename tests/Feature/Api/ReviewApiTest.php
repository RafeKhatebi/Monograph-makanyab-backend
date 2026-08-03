<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user', 'is_active' => true]);
    $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $this->category = PlaceCategory::create(['name' => 'Test '.uniqid(), 'slug' => 'test-'.uniqid(), 'is_active' => true]);
    $this->place = Place::factory()->create([
        'is_active' => true,
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
    ]);
});

test('authenticated user can create a review', function () {
    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'rating' => 5,
            'comment' => 'Great place!',
        ])
        ->assertCreated()
        ->assertJsonFragment([
            'rating' => 5,
            'is_approved' => false,
            'moderation_status' => Review::STATUS_PENDING,
        ]);
});

test('review requires rating', function () {
    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'place_id' => $this->place->id,
            'comment' => 'Great place!',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['rating']);
});

test('rating must be between 1 and 5', function () {
    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'place_id' => $this->place->id,
            'rating' => 6,
            'comment' => 'Great place!',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['rating']);
});

test('review comment is limited to two thousand characters', function () {
    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'rating' => 5,
            'comment' => str_repeat('a', 2001),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['comment']);
});

test('user can only review a place once', function () {
    Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
        'rating' => 4,
    ]);

    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'place_id' => $this->place->id,
            'rating' => 5,
            'comment' => 'Second review',
        ])
        ->assertUnprocessable();
});

test('authenticated user can view approved reviews', function () {
    $otherUser = User::factory()->create(['role' => 'user']);
    Review::factory()->create([
        'place_id' => $this->place->id,
        'is_approved' => true,
        'rating' => 5,
        'user_id' => $this->user->id,
    ]);
    Review::factory()->create([
        'place_id' => $this->place->id,
        'is_approved' => false,
        'rating' => 3,
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($this->user)
        ->getJson("/api/places/{$this->place->slug}/reviews")
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('review owner can delete their review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->deleteJson("/api/places/{$this->place->slug}/reviews/{$review->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
});

test('non-owner cannot delete review', function () {
    $otherUser = User::factory()->create(['role' => 'user']);
    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->deleteJson("/api/places/{$this->place->slug}/reviews/{$review->id}")
        ->assertForbidden();
});

test('review place is taken from the route and cannot be spoofed in the payload', function () {
    $otherPlace = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->user)
        ->postJson("/api/places/{$this->place->slug}/reviews", [
            'place_id' => $otherPlace->id,
            'rating' => 5,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);
});

test('review owner can edit their review and it returns to moderation', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
        'rating' => 2,
        'is_approved' => true,
    ]);

    $this->actingAs($this->user)
        ->patchJson("/api/places/{$this->place->slug}/reviews/{$review->id}", [
            'rating' => 4,
            'comment' => 'Updated review',
        ])
        ->assertOk()
        ->assertJsonFragment([
            'rating' => 4,
            'is_approved' => false,
            'moderation_status' => Review::STATUS_PENDING,
        ]);
});

test('non-owner cannot edit another users review', function () {
    $review = Review::factory()->create([
        'user_id' => User::factory()->create()->id,
        'place_id' => $this->place->id,
    ]);

    $this->actingAs($this->user)
        ->patchJson("/api/places/{$this->place->slug}/reviews/{$review->id}", [
            'rating' => 4,
        ])
        ->assertForbidden();
});

test('pending reviews are not exposed by the public review endpoint', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
        'is_approved' => false,
    ]);

    $this->getJson("/api/places/{$this->place->slug}/reviews/{$review->id}")
        ->assertNotFound();
});

test('rejected reviews are not exposed by public endpoints', function () {
    $review = Review::factory()->rejected()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
    ]);

    $this->getJson("/api/places/{$this->place->slug}/reviews")
        ->assertOk()
        ->assertJsonCount(0, 'data');

    $this->getJson("/api/places/{$this->place->slug}/reviews/{$review->id}")
        ->assertNotFound();
});

test('place average rating and rating filter use approved reviews only', function () {
    Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $this->place->id,
        'rating' => 2,
        'moderation_status' => Review::STATUS_APPROVED,
    ]);
    Review::factory()->pending()->create([
        'user_id' => User::factory()->create()->id,
        'place_id' => $this->place->id,
        'rating' => 5,
    ]);
    Review::factory()->rejected()->create([
        'user_id' => User::factory()->create()->id,
        'place_id' => $this->place->id,
        'rating' => 5,
    ]);

    expect($this->place->fresh()->avg_rating)->toBe(2.0)
        ->and(Place::query()->filterRatingAtLeast(3)->whereKey($this->place->id)->exists())->toBeFalse();
});

test('review must target exactly one place or service', function () {
    expect(fn () => Review::create([
        'user_id' => $this->user->id,
        'rating' => 5,
    ]))->toThrow(InvalidArgumentException::class);
});

test('admin can approve reject filter and delete reviews', function () {
    $pending = Review::factory()->pending()->create([
        'place_id' => $this->place->id,
        'user_id' => $this->user->id,
    ]);
    $approved = Review::factory()->create([
        'place_id' => $this->place->id,
        'user_id' => User::factory()->create()->id,
    ]);
    $rejected = Review::factory()->rejected()->create([
        'place_id' => $this->place->id,
        'user_id' => User::factory()->create()->id,
    ]);

    $this->actingAs($this->admin)
        ->get('/admin/reviews?status=pending')
        ->assertOk()
        ->assertSee('Pending')
        ->assertDontSee('Rejected during moderation');

    $this->actingAs($this->admin)
        ->get('/admin/reviews?status=approved&rating='.$approved->rating.'&target=place')
        ->assertOk()
        ->assertSee('Approved');

    $this->actingAs($this->admin)
        ->post("/admin/reviews/{$pending->id}/approve")
        ->assertRedirect();
    expect($pending->fresh()->moderation_status)->toBe(Review::STATUS_APPROVED)
        ->and($pending->fresh()->is_approved)->toBeTrue();

    $this->actingAs($this->admin)
        ->post("/admin/reviews/{$approved->id}/reject")
        ->assertRedirect();
    expect($approved->fresh()->moderation_status)->toBe(Review::STATUS_REJECTED)
        ->and($approved->fresh()->is_approved)->toBeFalse();

    $this->actingAs($this->admin)
        ->delete("/admin/reviews/{$rejected->id}")
        ->assertRedirect(route('admin.reviews.index'));
    $this->assertDatabaseMissing('reviews', ['id' => $rejected->id]);
});
