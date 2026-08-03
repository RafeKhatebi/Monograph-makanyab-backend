<?php

use App\Models\OpeningHour;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = User::factory()->create(['role' => 'owner']);
    $category = PlaceCategory::create(['name' => 'Shops', 'slug' => 'shops', 'is_active' => true]);
    $this->place = Place::factory()->create([
        'user_id' => $this->owner->id,
        'place_category_id' => $category->id,
    ]);
});

test('opening hour place id is derived from the route', function () {
    $otherPlace = Place::factory()->create();

    $this->actingAs($this->owner)
        ->postJson('/api/places/'.$this->place->slug.'/opening-hours', [
            'place_id' => $otherPlace->id,
            'day_of_week' => 1,
            'open_time' => '09:00',
            'close_time' => '17:00',
        ])
        ->assertCreated()
        ->assertJsonFragment(['place_id' => $this->place->id]);
});

test('opening hour update validates time order', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
        'is_closed' => false,
    ]);

    $this->actingAs($this->owner)
        ->putJson('/api/places/'.$this->place->slug.'/opening-hours/'.$hour->id, [
            'open_time' => '18:00',
            'close_time' => '10:00',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('close_time');
});

test('non-owner cannot update opening hours', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'is_closed' => true,
    ]);

    $this->actingAs(User::factory()->create())
        ->putJson('/api/places/'.$this->place->slug.'/opening-hours/'.$hour->id, [
            'is_closed' => true,
        ])
        ->assertForbidden();
});

test('opening hour cannot be updated through a different place route', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'is_closed' => true,
    ]);
    $otherPlace = Place::factory()->create(['user_id' => $this->owner->id]);

    $this->actingAs($this->owner)
        ->putJson('/api/places/'.$otherPlace->slug.'/opening-hours/'.$hour->id, [
            'is_closed' => true,
        ])
        ->assertForbidden();
});
