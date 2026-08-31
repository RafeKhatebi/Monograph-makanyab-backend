<?php

use App\Models\OpeningHour;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

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

test('opening hour update rejects identical times', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
        'is_closed' => false,
    ]);

    $this->actingAs($this->owner)
        ->putJson('/api/places/'.$this->place->slug.'/opening-hours/'.$hour->id, [
            'open_time' => '10:00',
            'close_time' => '10:00',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('close_time');
});

test('a place can define all seven days and closed days have no times', function () {
    foreach (range(0, 6) as $day) {
        $response = $this->actingAs($this->owner)
            ->postJson('/api/places/'.$this->place->slug.'/opening-hours', [
                'day_of_week' => $day,
                'is_closed' => $day === 0,
                'open_time' => '09:00',
                'close_time' => '17:00',
            ]);

        $response->assertCreated();
    }

    expect($this->place->openingHours()->count())->toBe(7);
    $closed = $this->place->openingHours()->where('day_of_week', 0)->first();
    expect($closed->is_closed)->toBeTrue()
        ->and($closed->open_time)->toBeNull()
        ->and($closed->close_time)->toBeNull();
});

test('duplicate days are rejected', function () {
    OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
    ]);

    $this->actingAs($this->owner)
        ->postJson('/api/places/'.$this->place->slug.'/opening-hours', [
            'day_of_week' => 1,
            'open_time' => '10:00',
            'close_time' => '18:00',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['day_of_week']);
});

test('overnight hours are open through the next day boundary', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '22:00',
        'close_time' => '02:00',
        'is_closed' => false,
    ]);

    expect($hour->isOpenAt(Carbon::parse('2026-08-17 22:00', 'UTC')))->toBeTrue()
        ->and($hour->isOpenAt(Carbon::parse('2026-08-17 23:59', 'UTC')))->toBeTrue()
        ->and($hour->isOpenAt(Carbon::parse('2026-08-18 01:59', 'UTC')))->toBeTrue()
        ->and($hour->isOpenAt(Carbon::parse('2026-08-18 02:00', 'UTC')))->toBeFalse();
});

test('opening hours honor the configured timezone and boundaries', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
        'is_closed' => false,
    ]);

    expect($hour->isOpenAt(Carbon::parse('2026-08-17 09:00', 'Asia/Kabul'), 'Asia/Kabul'))->toBeTrue()
        ->and($hour->isOpenAt(Carbon::parse('2026-08-17 17:00', 'Asia/Kabul'), 'Asia/Kabul'))->toBeFalse()
        ->and($hour->isOpenAt(Carbon::parse('2026-08-17 12:00', 'UTC'), 'Asia/Kabul'))->toBeTrue();
});

test('open now filtering uses opening-hour intervals', function () {
    Carbon::setTestNow(Carbon::parse('2026-08-17 12:00', 'UTC'));
    OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
        'is_closed' => false,
    ]);

    expect(Place::query()->filterOpenNow(true)->whereKey($this->place->id)->exists())->toBeTrue();

    Carbon::setTestNow(Carbon::parse('2026-08-17 17:00', 'UTC'));
    expect(Place::query()->filterOpenNow(true)->whereKey($this->place->id)->exists())->toBeFalse();
    Carbon::setTestNow();
});

test('owner can edit and delete opening hours', function () {
    $hour = OpeningHour::create([
        'place_id' => $this->place->id,
        'day_of_week' => 1,
        'open_time' => '09:00',
        'close_time' => '17:00',
        'is_closed' => false,
    ]);

    $this->actingAs($this->owner)
        ->putJson('/api/places/'.$this->place->slug.'/opening-hours/'.$hour->id, [
            'open_time' => '10:00',
            'close_time' => '18:00',
        ])
        ->assertOk()
        ->assertJsonFragment(['open_time' => '10:00']);

    $this->actingAs($this->owner)
        ->deleteJson('/api/places/'.$this->place->slug.'/opening-hours/'.$hour->id)
        ->assertNoContent();
    $this->assertDatabaseMissing('opening_hours', ['id' => $hour->id]);
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
