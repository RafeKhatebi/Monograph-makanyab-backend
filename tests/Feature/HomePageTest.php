<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

it('renders the phase nine home sections and searchable hero', function () {
    $placeCategory = PlaceCategory::factory()->create([
        'name' => 'Restaurants',
        'slug' => 'restaurants',
        'is_active' => true,
        'sort_order' => 1,
    ]);
    $serviceCategory = ServiceCategory::factory()->create([
        'name' => 'Repairs',
        'slug' => 'repairs',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Place::factory()->verified()->create([
        'place_category_id' => $placeCategory->id,
        'name' => 'Kabul Garden Cafe',
        'slug' => 'kabul-garden-cafe',
        'province' => 'Kabul',
        'is_active' => true,
    ]);
    Service::factory()->verified()->create([
        'service_category_id' => $serviceCategory->id,
        'name' => 'City Repair Service',
        'slug' => 'city-repair-service',
        'province' => 'Herat',
        'is_active' => true,
    ]);
    Post::factory()->create([
        'title' => 'Makanyab City Guide',
        'slug' => 'makanyab-city-guide',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('data-home-hero', false)
        ->assertSee('Find trusted places across Afghanistan')
        ->assertSee('Search places, services, or keywords')
        ->assertSee('Any location')
        ->assertSee('Restaurants')
        ->assertSee('Featured Places')
        ->assertSee('Featured Services')
        ->assertSee('Recently Verified')
        ->assertSee('Latest from Makanyab')
        ->assertSee('Kabul Garden Cafe')
        ->assertSee('City Repair Service')
        ->assertSee('Makanyab City Guide')
        ->assertSee('Suggest a Place')
        ->assertSee('Suggest a Service')
        ->assertDontSee('Show next Makanyab highlight');
});

it('shows professional empty states when home records are unavailable', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('No featured places yet.')
        ->assertSee('No featured services yet.')
        ->assertSee('No categories available yet.')
        ->assertSee('Verified places will appear here soon.')
        ->assertSee('New articles and updates will be published soon.');
});
