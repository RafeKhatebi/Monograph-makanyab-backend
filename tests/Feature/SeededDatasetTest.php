<?php

use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\Media;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder creates realistic browsing and moderation data', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::where('role', 'admin')->exists())->toBeTrue()
        ->and(User::where('role', 'owner')->count())->toBeGreaterThanOrEqual(6)
        ->and(User::where('role', 'user')->where('is_active', false)->count())->toBeGreaterThanOrEqual(3)
        ->and(PlaceCategory::active()->count())->toBeGreaterThanOrEqual(6)
        ->and(ServiceCategory::active()->count())->toBeGreaterThanOrEqual(6)
        ->and(PlaceCategory::doesntHave('places')->count())->toBeGreaterThanOrEqual(2)
        ->and(ServiceCategory::doesntHave('services')->count())->toBeGreaterThanOrEqual(2)
        ->and(Place::count())->toBeGreaterThanOrEqual(28)
        ->and(Place::onlyTrashed()->count())->toBeGreaterThanOrEqual(1)
        ->and(Service::count())->toBeGreaterThanOrEqual(26)
        ->and(Service::onlyTrashed()->count())->toBeGreaterThanOrEqual(1)
        ->and(Media::count())->toBeGreaterThanOrEqual(70)
        ->and(Review::where('is_approved', true)->count())->toBeGreaterThanOrEqual(30)
        ->and(Review::where('is_approved', false)->count())->toBeGreaterThanOrEqual(60)
        ->and(Favorite::whereNotNull('place_id')->count())->toBeGreaterThanOrEqual(12)
        ->and(Favorite::whereNotNull('service_id')->count())->toBeGreaterThanOrEqual(12)
        ->and(PlaceSuggestion::where('suggestion_status', 'pending')->count())->toBeGreaterThanOrEqual(6)
        ->and(ServiceSuggestion::where('suggestion_status', 'rejected')->count())->toBeGreaterThanOrEqual(2)
        ->and(Post::where('is_published', true)->count())->toBeGreaterThanOrEqual(13)
        ->and(ContactMessage::count())->toBeGreaterThanOrEqual(10);
});

test('database seeder includes layout edge cases', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Place::where('name', 'Duplicate Display Name')->count())->toBe(2)
        ->and(Place::where('name', 'like', '%Historic Community Marketplace%')->exists())->toBeTrue()
        ->and(Place::where('name', 'رستورانت خانوادگی کابل')->exists())->toBeTrue()
        ->and(Service::where('name', 'خدمات ترمیمات خانه')->exists())->toBeTrue()
        ->and(Post::where('title', 'راهنمای پیدا کردن خدمات در کابل')->exists())->toBeTrue()
        ->and(Place::doesntHave('media')->count())->toBeGreaterThanOrEqual(2)
        ->and(Service::doesntHave('media')->count())->toBeGreaterThanOrEqual(2)
        ->and(Place::doesntHave('reviews')->count())->toBeGreaterThanOrEqual(8)
        ->and(Service::doesntHave('reviews')->count())->toBeGreaterThanOrEqual(8);
});
