<?php

use App\Models\Place;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\PlaceCategory;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->placeCategory = PlaceCategory::factory()->create(['is_active' => true]);
    $this->serviceCategory = ServiceCategory::factory()->create(['is_active' => true]);
    $this->createService = function (array $overrides = []) {
        return Service::factory()->create(array_merge([
            'user_id' => $this->user->id,
            'service_category_id' => $this->serviceCategory->id,
            'is_active' => true,
        ], $overrides));
    };
});

test('places load-more endpoint returns next page as JSON', function () {
    Place::factory()->count(20)->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'is_active' => true,
    ]);

    $response = $this->getJson(route('places.load-more', ['page' => 2]));

    $response->assertOk()
        ->assertJsonStructure(['html', 'hasMore', 'nextPage'])
        ->assertJson(['hasMore' => false, 'nextPage' => null]);
});

test('services load-more endpoint returns next page as JSON', function () {
    for ($i = 0; $i < 20; $i++) {
        ($this->createService)(['name' => 'Service '.($i + 1)]);
    }

    $response = $this->getJson(route('services.load-more', ['page' => 2]));

    $response->assertOk()
        ->assertJsonStructure(['html', 'hasMore', 'nextPage'])
        ->assertJson(['hasMore' => false, 'nextPage' => null]);
});

test('places index render shows load more button with data attributes', function () {
    Place::factory()->count(20)->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'is_active' => true,
    ]);

    $this->get(route('places.index'))
        ->assertOk()
        ->assertSee('data-load-more-wrap', false)
        ->assertSee('data-next-page="2"', false)
        ->assertSee('data-endpoint="'.route('places.load-more').'"', false)
        ->assertSee('data-load-more-trigger', false);
});

test('services index render shows load more button with data attributes', function () {
    for ($i = 0; $i < 20; $i++) {
        ($this->createService)(['name' => 'Service '.($i + 1)]);
    }

    $this->get(route('services.index'))
        ->assertOk()
        ->assertSee('data-load-more-wrap', false)
        ->assertSee('data-next-page="2"', false)
        ->assertSee('data-endpoint="'.route('services.load-more').'"', false)
        ->assertSee('data-load-more-trigger', false);
});
