<?php

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $this->category = ServiceCategory::create([
        'name' => 'Repairs',
        'slug' => 'repairs',
        'is_active' => true,
    ]);
    $this->pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
    $this->payload = [
        'name' => 'Photo Service',
        'service_category_id' => $this->category->id,
        'description' => 'A service with photos.',
        'phone_1' => '+93000000000',
        'address' => 'Main Street',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'Kabul',
        'is_active' => '1',
    ];
});

test('admin can upload and display a service image with metadata', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post('/admin/services', $this->payload + [
            'images' => [UploadedFile::fake()->createWithContent('service.png', $this->pngBytes)],
            'cover_image_index' => 0,
        ])
        ->assertRedirect(route('admin.services.index'))
        ->assertSessionHasNoErrors();

    $service = Service::where('name', 'Photo Service')->firstOrFail();
    $media = $service->media->first();

    expect($media->is_cover)->toBeTrue()
        ->and($media->mime_type)->toBe('image/png')
        ->and($media->file_size)->toBeGreaterThan(0);
    Storage::disk('public')->assertExists($media->file_path);

    $this->get('/services/'.$service->slug)
        ->assertOk()
        ->assertSee('storage/'.$media->file_path, false);
});

test('duplicate service image content is stored only once', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post('/admin/services', $this->payload + [
            'images' => [
                UploadedFile::fake()->createWithContent('one.png', $this->pngBytes),
                UploadedFile::fake()->createWithContent('same-content.png', $this->pngBytes),
            ],
        ])
        ->assertRedirect(route('admin.services.index'));

    expect(Service::where('name', 'Photo Service')->firstOrFail()->media)->toHaveCount(1);
});

test('admin can remove a service image during edit', function () {
    Storage::fake('public');
    $service = Service::create([
        ...$this->payload,
        'user_id' => $this->admin->id,
        'slug' => 'photo-service',
        'status' => 'open',
        'price_level' => 'medium',
    ]);
    Storage::disk('public')->put('services/remove.png', $this->pngBytes);
    $media = $service->media()->create([
        'file_path' => 'services/remove.png',
        'disk' => 'public',
        'type' => 'image',
        'is_cover' => true,
    ]);

    $this->actingAs($this->admin)
        ->put('/admin/services/'.$service->slug, $this->payload + [
            'remove_media' => [$media->id],
        ])
        ->assertRedirect(route('admin.services.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseMissing('media', ['id' => $media->id]);
    Storage::disk('public')->assertMissing('services/remove.png');
});
