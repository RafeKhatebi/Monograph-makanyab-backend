<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Models\User;
use App\Services\SuggestionAdminService;
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

test('guest can submit a place suggestion without a user association', function () {
    $this->postJson('/api/suggestions/place', [
        'name' => 'Guest Place',
        'place_category_id' => $this->placeCategory->id,
        'phone_1' => '+1234567890',
        'address' => 'Guest Street',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
        'price_level' => 'medium',
        'submitted_by_name' => 'Guest User',
        'submitted_by_email' => 'guest@example.com',
    ])->assertCreated();

    $this->assertDatabaseHas('place_suggestions', [
        'name' => 'Guest Place',
        'user_id' => null,
        'submitted_by_email' => 'guest@example.com',
    ]);
});

test('duplicate place suggestions are rejected for the same category and city', function () {
    PlaceSuggestion::factory()->create([
        'name' => 'Repeated Place',
        'place_category_id' => $this->placeCategory->id,
        'city' => 'Kabul',
    ]);

    $this->actingAs($this->user)
        ->postJson('/api/suggestions/place', [
            'name' => 'repeated place',
            'place_category_id' => $this->placeCategory->id,
            'phone_1' => '+1234567890',
            'address' => 'Another Street',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'city' => 'Kabul',
            'district' => 'District 1',
            'price_level' => 'medium',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('duplicate service suggestions are rejected for the same category and city', function () {
    ServiceSuggestion::factory()->create([
        'name' => 'Repeated Service',
        'service_category_id' => $this->serviceCategory->id,
        'city' => 'Kabul',
    ]);

    $this->actingAs($this->user)
        ->postJson('/api/suggestions/service', [
            'name' => 'Repeated Service',
            'service_category_id' => $this->serviceCategory->id,
            'phone_1' => '+1234567890',
            'address' => 'Another Street',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'city' => 'Kabul',
            'district' => 'District 1',
            'price_level' => 'medium',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('admin queue rejects an invalid suggestion status', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->getJson('/api/admin/suggestions/places?status=unknown')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

test('admin approval converts a place suggestion with a unique slug and category', function () {
    $suggestion = PlaceSuggestion::factory()->create([
        'name' => 'Approved Place',
        'place_category_id' => $this->placeCategory->id,
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->post("/admin/place-suggestions/{$suggestion->id}/approve", ['admin_note' => 'Verified'])
        ->assertRedirect();

    $this->assertDatabaseHas('places', [
        'name' => 'Approved Place',
        'slug' => 'approved-place',
        'place_category_id' => $this->placeCategory->id,
    ]);
    expect($suggestion->fresh()->suggestion_status->value)->toBe('approved');
});

test('admin can reject a service suggestion without creating a service', function () {
    $suggestion = ServiceSuggestion::factory()->create([
        'name' => 'Rejected Service',
        'service_category_id' => $this->serviceCategory->id,
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->post("/admin/service-suggestions/{$suggestion->id}/reject", ['admin_note' => 'Not verified'])
        ->assertRedirect();

    expect($suggestion->fresh()->suggestion_status->value)->toBe('rejected')
        ->and(Service::where('name', 'Rejected Service')->exists())->toBeFalse();
});

test('suggestion approval rolls back the catalogue record when status update fails', function () {
    $suggestion = PlaceSuggestion::factory()->create([
        'name' => 'Rolled Back Place',
        'place_category_id' => $this->placeCategory->id,
    ]);

    PlaceSuggestion::updating(function () {
        throw new \RuntimeException('Simulated status update failure.');
    });

    try {
        app(SuggestionAdminService::class)->approve($suggestion, Place::class);
    } catch (\RuntimeException) {
        // The assertions below verify the transaction boundary.
    }

    $this->assertDatabaseMissing('places', ['name' => 'Rolled Back Place']);
    expect($suggestion->fresh()->suggestion_status->value)->toBe('pending');
});
