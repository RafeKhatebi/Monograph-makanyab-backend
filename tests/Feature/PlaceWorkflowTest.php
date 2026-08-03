<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps inactive and soft deleted places out of public listing and detail pages', function () {
    $category = PlaceCategory::factory()->create(['is_active' => true]);
    $active = Place::factory()->create([
        'name' => 'Visible Place',
        'slug' => 'visible-place',
        'place_category_id' => $category->id,
        'is_active' => true,
    ]);
    $inactive = Place::factory()->create([
        'name' => 'Inactive Place',
        'slug' => 'inactive-place',
        'place_category_id' => $category->id,
        'is_active' => false,
    ]);
    $deleted = Place::factory()->create([
        'name' => 'Deleted Place',
        'slug' => 'deleted-place',
        'place_category_id' => $category->id,
        'is_active' => true,
    ]);
    $deleted->delete();

    $this->get(route('places.index'))
        ->assertOk()
        ->assertSee($active->name)
        ->assertDontSee($inactive->name)
        ->assertDontSee($deleted->name);

    $this->get(route('places.show', $inactive))->assertNotFound();
    $this->get('/places/deleted-place')->assertNotFound();
});

it('validates public place listing filters', function () {
    $this->get(route('places.index', ['status' => 'invalid']))
        ->assertSessionHasErrors('status');

    $this->get(route('places.index', ['price_level' => 'free']))
        ->assertSessionHasErrors('price_level');

    $this->get(route('places.index', ['rating' => 9]))
        ->assertSessionHasErrors('rating');
});

it('generates unique slugs for duplicate place names in admin CRUD', function () {
    $admin = User::factory()->admin()->create();
    $category = PlaceCategory::factory()->create(['is_active' => true]);

    $payload = [
        'name' => 'Duplicate Display Name',
        'place_category_id' => $category->id,
        'description' => 'Duplicate slug test.',
        'address' => 'Test address',
        'phone_1' => '+93000000000',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'district' => 'Kabul',
        'latitude' => 34.5553,
        'longitude' => 69.2075,
    ];

    $this->actingAs($admin)->post(route('admin.places.store'), $payload)->assertRedirect();
    $this->actingAs($admin)->post(route('admin.places.store'), $payload)->assertRedirect();

    expect(Place::where('name', 'Duplicate Display Name')->orderBy('created_at')->pluck('slug')->all())
        ->toEqual(['duplicate-display-name', 'duplicate-display-name-1']);
});

it('allows admins to restore soft deleted places', function () {
    $admin = User::factory()->admin()->create();
    $place = Place::factory()->create(['name' => 'Restorable Place', 'slug' => 'restorable-place']);
    $place->delete();

    $this->actingAs($admin)
        ->get(route('admin.places.index', ['trashed' => 'only']))
        ->assertOk()
        ->assertSee('Restorable Place')
        ->assertSee('Restore Restorable Place', false);

    $this->actingAs($admin)
        ->post(route('admin.places.restore', 'restorable-place'))
        ->assertRedirect(route('admin.places.index', ['trashed' => 'with']));

    expect($place->fresh()->trashed())->toBeFalse();
});

it('rejects inactive categories when creating places through the admin form', function () {
    $admin = User::factory()->admin()->create();
    $inactiveCategory = PlaceCategory::factory()->inactive()->create();

    $this->actingAs($admin)
        ->from(route('admin.places.create'))
        ->post(route('admin.places.store'), [
            'name' => 'Inactive Category Place',
            'place_category_id' => $inactiveCategory->id,
            'description' => 'Should not be created.',
            'address' => 'Test address',
            'phone_1' => '+93000000000',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'district' => 'Kabul',
            'latitude' => 34.5553,
            'longitude' => 69.2075,
        ])
        ->assertRedirect(route('admin.places.create'))
        ->assertSessionHasErrors('place_category_id');

    $this->assertDatabaseMissing('places', ['name' => 'Inactive Category Place']);
});
