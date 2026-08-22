<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $this->category = PlaceCategory::create([
        'name' => 'Test Category '.uniqid(),
        'slug' => 'test-category-'.uniqid(),
        'is_active' => true,
    ]);
    $this->jpegBytes = base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=');
    $this->pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
});

test('admin can access places index', function () {
    $this->actingAs($this->admin)
        ->get('/admin/places')
        ->assertOk();
});

test('admin can access create place form', function () {
    $this->actingAs($this->admin)
        ->get('/admin/places/create')
        ->assertOk();
});

test('admin can create a place', function () {
    $data = [
        'name' => 'New Place',
        'place_category_id' => $this->category->id,
        'description' => 'A test place description',
        'address' => '123 Test Street',
        'phone_1' => '+1234567890',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'district' => 'Kabul',
        'latitude' => 34.5553,
        'longitude' => 69.2075,
        'is_active' => '1',
    ];

    $this->actingAs($this->admin)
        ->post('/admin/places', $data)
        ->assertRedirect();

    $this->assertDatabaseHas('places', ['name' => 'New Place']);
});

test('admin can view a place', function () {
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->get("/admin/places/{$place->slug}")
        ->assertOk();
});

test('admin can access edit place form', function () {
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->get("/admin/places/{$place->slug}/edit")
        ->assertOk();
});

test('admin can update a place', function () {
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->put("/admin/places/{$place->slug}", [
            'name' => 'Updated Place',
            'place_category_id' => $this->category->id,
            'description' => 'Updated description',
            'address' => '456 Updated Street',
            'phone_1' => '+9876543210',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'district' => 'Bagrami',
            'latitude' => 34.5553,
            'longitude' => 69.2075,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('places', ['id' => $place->id, 'name' => 'Updated Place']);
});

test('admin can delete a place', function () {
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->delete("/admin/places/{$place->slug}")
        ->assertRedirect();

    $this->assertSoftDeleted('places', ['id' => $place->id]);
});

test('admin can toggle place verification', function () {
    $place = Place::factory()->create([
        'is_verified' => false,
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->post("/admin/places/{$place->slug}/toggle-verification")
        ->assertRedirect();

    $this->assertTrue($place->fresh()->is_verified);
});

test('admin can toggle place active status', function () {
    $place = Place::factory()->create([
        'is_active' => true,
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->post("/admin/places/{$place->slug}/toggle-active")
        ->assertRedirect();

    $this->assertFalse($place->fresh()->is_active);
});

test('admin can filter places by search', function () {
    Place::factory()->create(['name' => 'Kabul Restaurant', 'user_id' => $this->admin->id, 'place_category_id' => $this->category->id, 'city' => 'Kabul']);
    Place::factory()->create(['name' => 'Mazar Shop', 'user_id' => $this->admin->id, 'place_category_id' => $this->category->id, 'city' => 'Mazar']);

    $this->actingAs($this->admin)
        ->get('/admin/places?search=Kabul')
        ->assertOk()
        ->assertSee('Kabul Restaurant')
        ->assertDontSee('Mazar Shop');
});

test('admin can filter places by category', function () {
    Place::factory()->create(['place_category_id' => $this->category->id, 'user_id' => $this->admin->id]);
    $otherCategory = PlaceCategory::create(['name' => 'Other '.uniqid(), 'slug' => 'other-'.uniqid(), 'is_active' => true]);
    Place::factory()->create(['place_category_id' => $otherCategory->id, 'user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get("/admin/places?category={$this->category->id}")
        ->assertOk();
});

test('admin can filter places by verification status', function () {
    Place::factory()->create(['is_verified' => true, 'user_id' => $this->admin->id, 'place_category_id' => $this->category->id]);
    Place::factory()->create(['is_verified' => false, 'user_id' => $this->admin->id, 'place_category_id' => $this->category->id]);

    $this->actingAs($this->admin)
        ->get('/admin/places?is_verified=1')
        ->assertOk();

    $this->actingAs($this->admin)
        ->get('/admin/places?is_verified=0')
        ->assertOk();
});

test('admin place form uses dependent province and district controls', function () {
    $this->actingAs($this->admin)
        ->get('/admin/places/create')
        ->assertOk()
        ->assertSee('id="province-select"', false)
        ->assertSee('id="district-select"', false)
        ->assertSee('"Kabul":["Kabul","Bagrami"', false);
});

test('admin can create places for valid province and district combinations', function (string $province, string $district) {
    $this->actingAs($this->admin)
        ->post('/admin/places', [
            'name' => "{$district} Place",
            'place_category_id' => $this->category->id,
            'description' => 'A valid location combination.',
            'address' => 'Test address',
            'phone_1' => '+93000000000',
            'country' => 'Afghanistan',
            'province' => $province,
            'district' => $district,
            'latitude' => 34.5553,
            'longitude' => 69.2075,
        ])
        ->assertRedirect(route('admin.places.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('places', [
        'name' => "{$district} Place",
        'province' => $province,
        'district' => $district,
        'city' => $district,
    ]);
})->with([
    ['Kabul', 'Bagrami'],
    ['Balkh', 'Mazar-e-Sharif'],
    ['Herat', 'Injil'],
    ['Nangarhar', 'Jalalabad'],
]);

test('admin place form rejects a district from another province and preserves input', function () {
    $this->actingAs($this->admin)
        ->from('/admin/places/create')
        ->post('/admin/places', [
            'name' => 'Invalid Location Place',
            'place_category_id' => $this->category->id,
            'description' => 'Invalid location combination.',
            'address' => 'Remember this address',
            'phone_1' => '+93000000000',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'district' => 'Mazar-e-Sharif',
            'latitude' => 34.5553,
            'longitude' => 69.2075,
        ])
        ->assertRedirect('/admin/places/create')
        ->assertSessionHasErrors('district')
        ->assertSessionHasInput('address', 'Remember this address');

    $this->assertDatabaseMissing('places', ['name' => 'Invalid Location Place']);
});

test('admin place upload stores metadata and selects a cover image', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post('/admin/places', [
            'name' => 'Place With Photos',
            'place_category_id' => $this->category->id,
            'description' => 'Photo upload test.',
            'address' => 'Test address',
            'phone_1' => '+93000000000',
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'district' => 'Kabul',
            'latitude' => 34.5553,
            'longitude' => 69.2075,
            'images' => [
                UploadedFile::fake()->createWithContent('first.jpg', $this->jpegBytes),
                UploadedFile::fake()->createWithContent('cover.png', $this->pngBytes),
            ],
            'cover_image_index' => 1,
        ])
        ->assertRedirect(route('admin.places.index'))
        ->assertSessionHasNoErrors();

    $place = Place::where('name', 'Place With Photos')->firstOrFail();
    expect($place->media)->toHaveCount(2)
        ->and($place->media->where('is_cover', true)->first()->mime_type)->toBe('image/png')
        ->and($place->media->where('is_cover', true)->first()->file_size)->toBeGreaterThan(0);

    $place->media->each(fn ($media) => Storage::disk('public')->assertExists($media->file_path));
});

test('admin place upload rejects unsupported and oversized files', function () {
    Storage::fake('public');

    $base = [
        'name' => 'Bad Photo Place',
        'place_category_id' => $this->category->id,
        'description' => 'Photo validation test.',
        'address' => 'Test address',
        'phone_1' => '+93000000000',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'district' => 'Kabul',
        'latitude' => 34.5553,
        'longitude' => 69.2075,
    ];

    $this->actingAs($this->admin)
        ->post('/admin/places', $base + [
            'images' => [UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')],
        ])
        ->assertSessionHasErrors('images.0');

    $this->actingAs($this->admin)
        ->post('/admin/places', $base + [
            'images' => [UploadedFile::fake()->createWithContent(
                'large.jpg',
                $this->jpegBytes.str_repeat("\0", 2049 * 1024)
            )],
        ])
        ->assertSessionHasErrors('images.0');
});

test('admin can remove an existing place image while editing', function () {
    Storage::fake('public');
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
        'province' => 'Kabul',
        'district' => 'Kabul',
    ]);
    Storage::disk('public')->put('places/remove.jpg', 'image');
    $media = $place->media()->create([
        'file_path' => 'places/remove.jpg',
        'disk' => 'public',
        'type' => 'image',
        'is_cover' => true,
    ]);

    $this->actingAs($this->admin)
        ->put("/admin/places/{$place->slug}", [
            'name' => $place->name,
            'place_category_id' => $this->category->id,
            'description' => $place->description,
            'address' => $place->address,
            'phone_1' => $place->phone_1,
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'district' => 'Kabul',
            'latitude' => 34.5553,
            'longitude' => 69.2075,
            'remove_media' => [$media->id],
        ])
        ->assertRedirect(route('admin.places.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseMissing('media', ['id' => $media->id]);
    Storage::disk('public')->assertMissing('places/remove.jpg');
});

test('force deleting a place removes its media records and files', function () {
    Storage::fake('public');
    $place = Place::factory()->create([
        'user_id' => $this->admin->id,
        'place_category_id' => $this->category->id,
    ]);
    Storage::disk('public')->put('places/force-delete.jpg', 'image');
    $media = $place->media()->create([
        'file_path' => 'places/force-delete.jpg',
        'disk' => 'public',
        'type' => 'image',
    ]);

    $place->forceDelete();

    $this->assertDatabaseMissing('media', ['id' => $media->id]);
    Storage::disk('public')->assertMissing('places/force-delete.jpg');
});
