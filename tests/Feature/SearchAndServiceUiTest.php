<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->placeCategory = PlaceCategory::create([
        'name' => 'Restaurants',
        'slug' => 'restaurants',
        'is_active' => true,
    ]);
    $this->serviceCategory = ServiceCategory::create([
        'name' => 'Repairs',
        'slug' => 'repairs',
        'is_active' => true,
    ]);
    $userId = $this->user->id;
    $categoryId = $this->serviceCategory->id;
    $this->createService = fn (array $attributes = []) => Service::create(array_merge([
        'user_id' => $userId,
        'service_category_id' => $categoryId,
        'name' => 'Repair Service '.uniqid(),
        'slug' => 'repair-service-'.uniqid(),
        'description' => 'Professional repair service.',
        'phone_1' => '+93000000000',
        'address' => 'Main Street',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Kabul',
        'status' => 'open',
        'price_level' => 'medium',
        'is_active' => true,
        'is_verified' => false,
    ], $attributes));
});

test('search renders a discover panel and accessible filter toggles', function () {
    $this->get('/search?province=Kabul&verified=1')
        ->assertOk()
        ->assertSee('class="discover-panel"', false)
        ->assertSee('class="discover-more"', false);
});

test('search form uses clearer keyword province and type fields', function () {
    $this->get('/search?search=cafe&province=Karte%20Se&type=services&sort=relevance')
        ->assertOk()
        ->assertSee('Keyword')
        ->assertSee('Place, service, category, or keyword')
        ->assertSee('name="province"', false)
        ->assertSee('Search in')
        ->assertSee('Most relevant')
        ->assertSee('Services');
});

test('combined search keeps pagination with persisted filters', function () {
    Place::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'is_active' => true,
    ]);

    $this->get('/search')
        ->assertOk()
        ->assertSee('page=2', false);
});

test('search combines type location category status price and verified filters', function () {
    ($this->createService)([
        'name' => 'Matching Plumbing',
        'slug' => 'matching-plumbing',
        'province' => 'Herat',
        'city' => 'Herat',
        'district' => 'Injil',
        'status' => 'open',
        'price_level' => 'high',
        'is_verified' => true,
    ]);
    ($this->createService)([
        'name' => 'Other Plumbing',
        'slug' => 'other-plumbing',
        'province' => 'Kabul',
    ]);

    $this->get('/search?type=services&search=Plumbing&province=Herat&district=Injil&service_category=repairs&status=open&price_level=high&verified=1')
        ->assertOk()
        ->assertSee('Matching Plumbing')
        ->assertDontSee('Other Plumbing');
});

test('search matches places and services by category location address dari and special characters', function () {
    $medicalCategory = PlaceCategory::create([
        'name' => 'کلینیک صحی',
        'slug' => 'clinics',
        'keywords' => 'doctor health clinic',
        'is_active' => true,
    ]);

    $maintenanceCategory = ServiceCategory::create([
        'name' => 'Home Maintenance',
        'slug' => 'home-maintenance',
        'keywords' => 'ترمیم plumber electrician',
        'is_active' => true,
    ]);

    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $medicalCategory->id,
        'name' => 'Nawroz Family Center',
        'slug' => 'nawroz-family-center',
        'address' => 'Street 5, Karte Se',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Karte Se',
        'description' => 'پذیرایی و خدمات صحی برای خانواده ها.',
        'is_active' => true,
    ]);

    ($this->createService)([
        'service_category_id' => $maintenanceCategory->id,
        'name' => 'Fix & Go Repairs',
        'slug' => 'fix-go-repairs',
        'address' => 'Darulaman Road',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Darulaman',
    ]);

    $this->get('/search?'.http_build_query(['search' => 'کلینیک', 'type' => 'places']))
        ->assertOk()
        ->assertSee('Nawroz Family Center');

    $this->get('/search?'.http_build_query(['search' => 'doctor', 'type' => 'places']))
        ->assertOk()
        ->assertSee('Nawroz Family Center');

    $this->get('/search?'.http_build_query(['search' => 'Fix & Go', 'type' => 'services']))
        ->assertOk()
        ->assertSee('Fix &amp; Go Repairs', false);

    $this->get('/search?'.http_build_query(['location' => 'Darulaman', 'type' => 'services']))
        ->assertOk()
        ->assertSee('Fix &amp; Go Repairs', false);
});

test('search excludes inactive and deleted content from results', function () {
    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Visible Atlas Cafe',
        'slug' => 'visible-atlas-cafe',
        'is_active' => true,
    ]);

    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Hidden Atlas Cafe',
        'slug' => 'hidden-atlas-cafe',
        'is_active' => false,
    ]);

    $deleted = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Deleted Atlas Cafe',
        'slug' => 'deleted-atlas-cafe',
        'is_active' => true,
    ]);
    $deleted->delete();

    $this->get('/search?search=Atlas&type=places')
        ->assertOk()
        ->assertSee('Visible Atlas Cafe')
        ->assertDontSee('Hidden Atlas Cafe')
        ->assertDontSee('Deleted Atlas Cafe');
});

test('search relevance ranks exact and prefix matches before weaker matches', function () {
    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Garden Plaza',
        'slug' => 'garden-plaza-description',
        'description' => 'Atlas is mentioned only in the description.',
        'created_at' => now()->subDay(),
        'is_active' => true,
    ]);

    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Atlas Market',
        'slug' => 'atlas-market-prefix',
        'created_at' => now()->subDays(2),
        'is_active' => true,
    ]);

    Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'name' => 'Atlas',
        'slug' => 'atlas-exact',
        'created_at' => now()->subDays(3),
        'is_active' => true,
    ]);

    $this->get('/search?search=Atlas&type=places')
        ->assertOk()
        ->assertSeeInOrder(['Atlas', 'Atlas Market', 'Garden Plaza']);
});

test('search sorting and filters persist through pagination links', function () {
    foreach (range(1, 19) as $number) {
        ($this->createService)([
            'name' => sprintf('Zahir Electric %02d', $number),
            'slug' => sprintf('zahir-electric-%02d', $number),
            'province' => 'Herat',
            'city' => 'Herat',
            'district' => 'Injil',
            'is_verified' => true,
        ]);
    }

    $this->get('/search?type=services&location=Herat&verified=1&sort=name_desc')
        ->assertOk()
        ->assertSee('location=Herat', false)
        ->assertSee('verified=1', false)
        ->assertSee('sort=name_desc', false)
        ->assertSee('page=2', false)
        ->assertSeeInOrder(['Zahir Electric 19', 'Zahir Electric 18']);
});

test('search rejects overly long free text and province values', function () {
    $longText = str_repeat('a', 121);

    $this->get('/search?'.http_build_query(['search' => $longText, 'province' => $longText]))
        ->assertSessionHasErrors(['search', 'province']);
});

test('service cards are shared by service search listing and category pages', function () {
    $service = ($this->createService)(['name' => 'Shared Card Service', 'slug' => 'shared-card-service']);

    $this->get('/services')->assertOk()->assertSee('place-card-col', false);
    $this->get('/search?type=services')->assertOk()->assertSee('place-card-col', false);
    $this->get('/service-categories/'.$this->serviceCategory->slug)
        ->assertOk()
        ->assertSee('place-card-col', false);
    $this->get('/services/'.$service->slug)
        ->assertOk()
        ->assertSee('Shared Card Service');
});

test('place show review form depends on controller review state', function () {
    $place = Place::factory()->create([
        'user_id' => $this->user->id,
        'place_category_id' => $this->placeCategory->id,
        'is_active' => true,
    ]);

    $this->actingAs($this->user)
        ->get('/places/'.$place->slug)
        ->assertOk()
        ->assertSee('Submit review')
        ->assertDontSee('You have already reviewed this place.');

    Review::factory()->create([
        'user_id' => $this->user->id,
        'place_id' => $place->id,
    ]);

    $this->actingAs($this->user)
        ->get('/places/'.$place->slug)
        ->assertOk()
        ->assertSee('You have already reviewed this place.')
        ->assertDontSee('Submit review');
});

test('service show review form depends on controller review state', function () {
    $service = ($this->createService)(['name' => 'Reviewable Service', 'slug' => 'reviewable-service']);

    $this->actingAs($this->user)
        ->get('/services/'.$service->slug)
        ->assertOk()
        ->assertSee('Submit review')
        ->assertDontSee('You have already reviewed this service.');

    Review::create([
        'user_id' => $this->user->id,
        'service_id' => $service->id,
        'rating' => 5,
    ]);

    $this->actingAs($this->user)
        ->get('/services/'.$service->slug)
        ->assertOk()
        ->assertSee('You have already reviewed this service.')
        ->assertDontSee('Submit review');
});

test('search rejects unsupported filter and sorting values', function () {
    $this->get('/search?status=invalid&sort=random')
        ->assertSessionHasErrors(['status', 'sort']);
});
