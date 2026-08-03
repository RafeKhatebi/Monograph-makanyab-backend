<?php

use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps inactive and soft deleted services out of public listing and detail pages', function () {
    $category = ServiceCategory::factory()->create(['is_active' => true]);
    $active = Service::factory()->create([
        'name' => 'Visible Service',
        'slug' => 'visible-service',
        'service_category_id' => $category->id,
        'is_active' => true,
    ]);
    $inactive = Service::factory()->create([
        'name' => 'Inactive Service',
        'slug' => 'inactive-service',
        'service_category_id' => $category->id,
        'is_active' => false,
    ]);
    $deleted = Service::factory()->create([
        'name' => 'Deleted Service',
        'slug' => 'deleted-service',
        'service_category_id' => $category->id,
        'is_active' => true,
    ]);
    $deleted->delete();

    $this->get(route('services.index'))
        ->assertOk()
        ->assertSee($active->name)
        ->assertDontSee($inactive->name)
        ->assertDontSee($deleted->name);

    $this->get(route('services.show', $inactive))->assertNotFound();
    $this->get('/services/deleted-service')->assertNotFound();
});

it('validates public service listing filters', function () {
    $this->get(route('services.index', ['status' => 'invalid']))
        ->assertSessionHasErrors('status');

    $this->get(route('services.index', ['price_level' => 'free']))
        ->assertSessionHasErrors('price_level');

    $this->get(route('services.index', ['rating' => 9]))
        ->assertSessionHasErrors('rating');
});

it('filters public services by rating and open status', function () {
    $category = ServiceCategory::factory()->create(['is_active' => true]);
    $matching = Service::factory()->create([
        'name' => 'Open Five Star Service',
        'service_category_id' => $category->id,
        'status' => 'open',
        'is_active' => true,
    ]);
    $closed = Service::factory()->create([
        'name' => 'Closed Five Star Service',
        'service_category_id' => $category->id,
        'status' => 'closed',
        'is_active' => true,
    ]);
    $lowRated = Service::factory()->create([
        'name' => 'Open Low Rated Service',
        'service_category_id' => $category->id,
        'status' => 'open',
        'is_active' => true,
    ]);

    Review::factory()->forService($matching)->create(['rating' => 5, 'is_approved' => true]);
    Review::factory()->forService($closed)->create(['rating' => 5, 'is_approved' => true]);
    Review::factory()->forService($lowRated)->create(['rating' => 2, 'is_approved' => true]);

    $this->get(route('services.index', ['rating' => 4, 'open_now' => 1]))
        ->assertOk()
        ->assertSee('Open Five Star Service')
        ->assertDontSee('Closed Five Star Service')
        ->assertDontSee('Open Low Rated Service');
});

it('generates unique slugs for duplicate service names in admin CRUD', function () {
    $admin = User::factory()->admin()->create();
    $category = ServiceCategory::factory()->create(['is_active' => true]);

    $payload = [
        'name' => 'Duplicate Service Name',
        'service_category_id' => $category->id,
        'description' => 'Duplicate slug test.',
        'phone_1' => '+93000000000',
        'address' => 'Test address',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Kabul',
        'is_active' => '1',
    ];

    $this->actingAs($admin)->post(route('admin.services.store'), $payload)->assertRedirect();
    $this->actingAs($admin)->post(route('admin.services.store'), $payload)->assertRedirect();

    expect(Service::where('name', 'Duplicate Service Name')->orderBy('created_at')->pluck('slug')->all())
        ->toEqual(['duplicate-service-name', 'duplicate-service-name-1']);
});

it('allows admins to restore soft deleted services', function () {
    $admin = User::factory()->admin()->create();
    $service = Service::factory()->create(['name' => 'Restorable Service', 'slug' => 'restorable-service']);
    $service->delete();

    $this->actingAs($admin)
        ->get(route('admin.services.index', ['trashed' => 'only']))
        ->assertOk()
        ->assertSee('Restorable Service')
        ->assertSee('Restore Restorable Service', false);

    $this->actingAs($admin)
        ->post(route('admin.services.restore', 'restorable-service'))
        ->assertRedirect(route('admin.services.index', ['trashed' => 'with']));

    expect($service->fresh()->trashed())->toBeFalse();
});

it('rejects inactive categories when creating services through the admin form', function () {
    $admin = User::factory()->admin()->create();
    $inactiveCategory = ServiceCategory::factory()->inactive()->create();

    $this->actingAs($admin)
        ->from(route('admin.services.create'))
        ->post(route('admin.services.store'), [
            'name' => 'Inactive Category Service',
            'service_category_id' => $inactiveCategory->id,
            'description' => 'Should not be created.',
            'phone_1' => '+93000000000',
            'address' => 'Test address',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'city' => 'Kabul',
            'district' => 'Kabul',
        ])
        ->assertRedirect(route('admin.services.create'))
        ->assertSessionHasErrors('service_category_id');

    $this->assertDatabaseMissing('services', ['name' => 'Inactive Category Service']);
});
