<?php

use App\Enums\SuggestionStatus;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
});

test('authenticated user can save an add place draft with an image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = PlaceCategory::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('add.store'), placePayload([
            'type' => 'place',
            'submit_action' => 'draft',
            'place_category_id' => $category->id,
            'images' => [UploadedFile::fake()->createWithContent('place.png', $this->pngBytes)],
        ]));

    $response
        ->assertRedirect(route('add.create', ['type' => 'place']))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $suggestion = PlaceSuggestion::firstOrFail();

    expect($suggestion->user_id)->toBe($user->id)
        ->and($suggestion->place_category_id)->toBe($category->id)
        ->and($suggestion->suggestion_status)->toBe(SuggestionStatus::Draft)
        ->and($suggestion->media)->toHaveCount(1);

    Storage::disk('public')->assertExists($suggestion->media->first()->file_path);

    $this
        ->actingAs($user)
        ->get(route('add.create', ['type' => 'place']))
        ->assertOk()
        ->assertDontSee('Kabul Community Hub')
        ->assertDontSee('suggestion-status-panel', false)
        ->assertDontSee('suggestion-branding__logo', false);
});

test('authenticated user can send an add service submission for review with an image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = ServiceCategory::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('add.store'), placePayload([
            'type' => 'service',
            'submit_action' => 'send_review',
            'service_category_id' => $category->id,
            'images' => [UploadedFile::fake()->createWithContent('service.png', $this->pngBytes)],
        ]));

    $response
        ->assertRedirect(route('add.create', ['type' => 'service']))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $suggestion = ServiceSuggestion::firstOrFail();

    expect($suggestion->user_id)->toBe($user->id)
        ->and($suggestion->service_category_id)->toBe($category->id)
        ->and($suggestion->suggestion_status)->toBe(SuggestionStatus::Pending)
        ->and($suggestion->media)->toHaveCount(1);

    Storage::disk('public')->assertExists($suggestion->media->first()->file_path);

    $this
        ->actingAs($user)
        ->get(route('add.create', ['type' => 'service']))
        ->assertOk()
        ->assertDontSee('Kabul Community Hub')
        ->assertDontSee('suggestion-status-panel', false)
        ->assertDontSee('suggestion-branding__logo', false);
});

test('authenticated user can save a post draft and view it in profile submissions', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('add.store'), [
            'type' => 'post',
            'submit_action' => 'draft',
            'title' => 'Useful Kabul Post',
            'excerpt' => 'A short summary for the post.',
            'content' => 'This is a complete draft post with enough useful local information for Makanyab readers to review later.',
            'extra_information' => 'Source checked by submitter.',
        ]);

    $response
        ->assertRedirect(route('add.create', ['type' => 'post']))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $post = Post::where('title', 'Useful Kabul Post')->firstOrFail();

    expect($post->user_id)->toBe($user->id)
        ->and($post->submission_status)->toBe(SuggestionStatus::Draft)
        ->and($post->is_published)->toBeFalse()
        ->and($post->image)->toBeNull();

    $this
        ->actingAs($user)
        ->get(route('profile.index'))
        ->assertOk()
        ->assertSeeText('Useful Kabul Post')
        ->assertSeeText('My Submissions');
});

test('authenticated user can send a post submission for admin review with an image', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('add.store'), [
            'type' => 'post',
            'submit_action' => 'send_review',
            'title' => 'Submitted Community Post',
            'excerpt' => 'A short summary for the submitted post.',
            'content' => 'This is a complete submitted post with enough useful local information for Makanyab readers and admin review.',
            'image' => UploadedFile::fake()->createWithContent('post.png', $this->pngBytes),
        ]);

    $response
        ->assertRedirect(route('add.create', ['type' => 'post']))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $post = Post::where('title', 'Submitted Community Post')->firstOrFail();

    expect($post->submission_status)->toBe(SuggestionStatus::UnderReview)
        ->and($post->is_published)->toBeFalse()
        ->and($post->image)->not->toBeNull();

    Storage::disk('public')->assertExists($post->image);
});

test('add place and service submissions show validation errors for missing required fields', function (string $type) {
    $response = $this
        ->actingAs(User::factory()->create())
        ->from(route('add.create', ['type' => $type]))
        ->post(route('add.store'), [
            'type' => $type,
            'submit_action' => 'send_review',
        ]);

    $response
        ->assertRedirect(route('add.create', ['type' => $type]))
        ->assertSessionHasErrors(['name', 'description', 'phone_1', 'address', 'province', 'city', 'district', 'price_level', 'images']);
})->with(['place', 'service']);

test('add submissions reject inactive categories', function (string $type, string $categoryModel, string $categoryField) {
    Storage::fake('public');

    $category = $categoryModel::factory()->inactive()->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->from(route('add.create', ['type' => $type]))
        ->post(route('add.store'), placePayload([
            'type' => $type,
            $categoryField => $category->id,
            'images' => [UploadedFile::fake()->createWithContent('submission.png', $this->pngBytes)],
        ]));

    $response->assertRedirect(route('add.create', ['type' => $type]))
        ->assertSessionHasErrors($categoryField);
})->with([
    ['place', PlaceCategory::class, 'place_category_id'],
    ['service', ServiceCategory::class, 'service_category_id'],
]);

test('add submissions reject duplicate pending items in the same category and city', function (string $type, string $categoryModel, string $suggestionModel, string $categoryField) {
    Storage::fake('public');

    $category = $categoryModel::factory()->create();
    $payload = placePayload([
        'type' => $type,
        $categoryField => $category->id,
        'name' => 'Duplicate Local Item',
        'city' => 'Kabul',
        'images' => [UploadedFile::fake()->createWithContent('submission.png', $this->pngBytes)],
    ]);

    $suggestionModel::factory()->create([
        $categoryField => $category->id,
        'name' => 'Duplicate Local Item',
        'city' => 'Kabul',
        'suggestion_status' => SuggestionStatus::Pending,
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->from(route('add.create', ['type' => $type]))
        ->post(route('add.store'), $payload);

    $response->assertRedirect(route('add.create', ['type' => $type]))
        ->assertSessionHasErrors('name');
})->with([
    ['place', PlaceCategory::class, PlaceSuggestion::class, 'place_category_id'],
    ['service', ServiceCategory::class, ServiceSuggestion::class, 'service_category_id'],
]);

test('admin can approve a pending place suggestion and publish it with media', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();
    $category = PlaceCategory::factory()->create();

    $this
        ->actingAs($user)
        ->post(route('add.store'), placePayload([
            'type' => 'place',
            'submit_action' => 'send_review',
            'place_category_id' => $category->id,
            'name' => 'Approved Place Submission',
            'images' => [UploadedFile::fake()->createWithContent('place.png', $this->pngBytes)],
        ]))
        ->assertSessionHasNoErrors();

    $suggestion = PlaceSuggestion::where('name', 'Approved Place Submission')->firstOrFail();
    $mediaPath = $suggestion->media()->firstOrFail()->file_path;

    $this
        ->actingAs($admin)
        ->get(route('admin.place-suggestions.index', ['status' => 'pending']))
        ->assertOk()
        ->assertSee('Approved Place Submission');

    $this
        ->actingAs($admin)
        ->post(route('admin.place-suggestions.approve', $suggestion), ['admin_note' => 'Verified'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $place = Place::where('name', 'Approved Place Submission')->firstOrFail();

    expect($suggestion->fresh()->suggestion_status)->toBe(SuggestionStatus::Approved)
        ->and($place->media)->toHaveCount(1)
        ->and($place->media->first()->file_path)->toBe($mediaPath);
});

test('admin can reject a pending service suggestion without publishing it', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();
    $category = ServiceCategory::factory()->create();

    $this
        ->actingAs($user)
        ->post(route('add.store'), placePayload([
            'type' => 'service',
            'submit_action' => 'send_review',
            'service_category_id' => $category->id,
            'name' => 'Rejected Service Submission',
            'images' => [UploadedFile::fake()->createWithContent('service.png', $this->pngBytes)],
        ]))
        ->assertSessionHasNoErrors();

    $suggestion = ServiceSuggestion::where('name', 'Rejected Service Submission')->firstOrFail();

    $this
        ->actingAs($admin)
        ->get(route('admin.service-suggestions.index', ['status' => 'pending']))
        ->assertOk()
        ->assertSee('Rejected Service Submission');

    $this
        ->actingAs($admin)
        ->post(route('admin.service-suggestions.reject', $suggestion), ['admin_note' => 'Incomplete'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($suggestion->fresh()->suggestion_status)->toBe(SuggestionStatus::Rejected)
        ->and(Service::where('name', 'Rejected Service Submission')->exists())->toBeFalse();
});

test('admin can approve a submitted post from the posts section', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->unpublished()->create([
        'title' => 'Post Waiting For Approval',
        'submission_status' => SuggestionStatus::UnderReview,
    ]);

    $this
        ->actingAs($admin)
        ->get(route('admin.posts.index', ['is_published' => '0']))
        ->assertOk()
        ->assertSeeText('Post Waiting For Approval');

    $this
        ->actingAs($admin)
        ->post(route('admin.posts.approve', $post))
        ->assertRedirect()
        ->assertSessionHas('success');

    $post->refresh();

    expect($post->is_published)->toBeTrue()
        ->and($post->submission_status)->toBe(SuggestionStatus::Published)
        ->and($post->published_at)->not->toBeNull();
});

test('users can save posts and see saved posts in profile and favorites', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Saved Community Guide']);

    $this
        ->actingAs($user)
        ->post(route('favorites.toggle'), ['post_id' => $post->id])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this
        ->actingAs($user)
        ->get(route('profile.index'))
        ->assertOk()
        ->assertSeeText('Saved Community Guide')
        ->assertSeeText('Saved posts');

    $this
        ->actingAs($user)
        ->get(route('favorites.index'))
        ->assertOk()
        ->assertSeeText('Saved Community Guide');
});

test('farsi add page does not show english suggestion interface text', function () {
    PlaceCategory::factory()->create(['name' => 'رستورانت']);
    ServiceCategory::factory()->create(['name' => 'ترمیمات']);

    $this
        ->actingAs(User::factory()->create())
        ->withSession(['locale' => 'fa'])
        ->get(route('add.create', ['type' => 'place']))
        ->assertOk()
        ->assertSeeText('افزودن به مکان‌یاب')
        ->assertSeeText('انگلیسی')
        ->assertDontSeeText('English')
        ->assertDontSeeText('Save Draft')
        ->assertDontSeeText('Send for Review');
});

test('farsi admin suggestion pages use translated interface text', function () {
    $admin = User::factory()->admin()->create();
    $category = PlaceCategory::factory()->create(['name' => 'کافه‌ها']);
    $suggestion = PlaceSuggestion::factory()->create([
        'place_category_id' => $category->id,
        'name' => 'کافه شهر',
        'city' => 'کابل',
        'province' => 'کابل',
        'address' => 'ناحیه سوم',
        'suggestion_status' => SuggestionStatus::Pending,
    ]);

    $this
        ->actingAs($admin)
        ->withSession(['locale' => 'fa'])
        ->get(route('admin.place-suggestions.index', ['status' => 'pending']))
        ->assertOk()
        ->assertSeeText('پیشنهادهای مکان')
        ->assertSeeText('در حال بررسی')
        ->assertDontSeeText('Pending Suggestions')
        ->assertDontSeeText('Filter')
        ->assertDontSeeText('View');

    $this
        ->actingAs($admin)
        ->withSession(['locale' => 'fa'])
        ->get(route('admin.place-suggestions.show', $suggestion))
        ->assertOk()
        ->assertSeeText('جزئیات پیشنهاد مکان')
        ->assertSeeText('عملیات مدیر')
        ->assertDontSeeText('Submitted by')
        ->assertDontSeeText('Back to Suggestions')
        ->assertDontSeeText('Approve')
        ->assertDontSeeText('Reject');
});

test('floating add button exposes exactly three submission options', function () {
    $this
        ->get(route('home'))
        ->assertOk()
        ->assertSee('class="mk-fab__trigger"', false)
        ->assertSee('id="mk-fab-menu"', false)
        ->assertSee('href="'.route('add.create', ['type' => 'place']).'"', false)
        ->assertSee('href="'.route('add.create', ['type' => 'service']).'"', false)
        ->assertSee('href="'.route('add.create', ['type' => 'post']).'"', false)
        ->assertSeeText('Suggest a Place')
        ->assertSeeText('Suggest a Service')
        ->assertSeeText('Create a Post');
});

test('admins cannot access direct create routes for places services or posts', function (string $path) {
    $this
        ->actingAs(User::factory()->admin()->create())
        ->get($path)
        ->assertNotFound();
})->with(['/admin/places/create', '/admin/services/create', '/admin/posts/create']);

function placePayload(array $overrides = []): array
{
    return array_merge([
        'type' => 'place',
        'submit_action' => 'send_review',
        'name' => 'Kabul Community Hub',
        'tagline' => 'Useful local place',
        'description' => 'A complete description for a useful local place or service in the city.',
        'phone_1' => '+93700111222',
        'phone_2' => '+93700333444',
        'whatsapp' => '+93700555666',
        'website' => 'https://example.com',
        'address' => 'Street 1, District 3',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 3',
        'subdistrict' => 'Subdistrict 1',
        'neighborhood' => 'Shahr-e Naw',
        'village' => null,
        'postal_code' => '1001',
        'latitude' => '34.5553',
        'longitude' => '69.2075',
        'status' => 'open',
        'price_level' => 'medium',
        'extra_information' => 'Open during normal business hours.',
    ], $overrides);
}
