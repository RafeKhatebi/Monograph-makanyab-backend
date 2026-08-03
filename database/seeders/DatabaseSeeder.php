<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\Media;
use App\Models\OpeningHour;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Makanyab Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $owners = User::factory()->owner()->count(6)->create();
        $users = User::factory()->count(18)->create();
        User::factory()->inactive()->count(3)->create();
        User::factory()->dariProfile()->create([
            'username' => 'dari_user',
            'email' => 'dari-user@example.com',
        ]);

        $placeCategories = PlaceCategory::factory()->count(6)->create();
        PlaceCategory::factory()->inactive()->count(2)->create();
        PlaceCategory::factory()->empty()->count(2)->create();

        $serviceCategories = ServiceCategory::factory()->count(6)->create();
        ServiceCategory::factory()->inactive()->count(2)->create();
        ServiceCategory::factory()->empty()->count(2)->create();

        $places = Place::factory()->count(24)->sequence(fn ($sequence) => [
            'user_id' => $owners[$sequence->index % $owners->count()]->id,
            'place_category_id' => $placeCategories[$sequence->index % $placeCategories->count()]->id,
            'is_verified' => $sequence->index % 3 === 0,
            'status' => ['open', 'closed', 'temporarily_closed'][$sequence->index % 3],
            'price_level' => ['low', 'medium', 'high', 'luxury'][$sequence->index % 4],
        ])->create();

        $places->push(Place::factory()->longContent()->create([
            'user_id' => $owners->first()->id,
            'place_category_id' => $placeCategories->first()->id,
        ]));
        $places->push(Place::factory()->dariContent()->create([
            'user_id' => $owners->get(1)->id,
            'place_category_id' => $placeCategories->get(1)->id,
        ]));
        $places->push(Place::factory()->create([
            'user_id' => $owners->get(2)->id,
            'place_category_id' => $placeCategories->get(2)->id,
            'name' => 'Duplicate Display Name',
            'slug' => 'duplicate-display-name-place-a',
        ]));
        $places->push(Place::factory()->create([
            'user_id' => $owners->get(3)->id,
            'place_category_id' => $placeCategories->get(2)->id,
            'name' => 'Duplicate Display Name',
            'slug' => 'duplicate-display-name-place-b',
        ]));
        Place::factory()->inactive()->count(3)->create([
            'user_id' => $owners->first()->id,
            'place_category_id' => $placeCategories->first()->id,
        ]);
        $softDeletedPlace = Place::factory()->create([
            'user_id' => $owners->first()->id,
            'place_category_id' => $placeCategories->first()->id,
        ]);
        $softDeletedPlace->delete();

        $services = Service::factory()->count(24)->sequence(fn ($sequence) => [
            'user_id' => $owners[$sequence->index % $owners->count()]->id,
            'service_category_id' => $serviceCategories[$sequence->index % $serviceCategories->count()]->id,
            'is_verified' => $sequence->index % 2 === 0,
            'status' => ['open', 'closed', 'temporarily_closed'][$sequence->index % 3],
            'price_level' => ['low', 'medium', 'high', 'luxury'][$sequence->index % 4],
        ])->create();

        $services->push(Service::factory()->longContent()->create([
            'user_id' => $owners->first()->id,
            'service_category_id' => $serviceCategories->first()->id,
        ]));
        $services->push(Service::factory()->dariContent()->create([
            'user_id' => $owners->get(1)->id,
            'service_category_id' => $serviceCategories->get(1)->id,
        ]));
        Service::factory()->inactive()->count(3)->create([
            'user_id' => $owners->first()->id,
            'service_category_id' => $serviceCategories->first()->id,
        ]);
        $softDeletedService = Service::factory()->create([
            'user_id' => $owners->first()->id,
            'service_category_id' => $serviceCategories->first()->id,
        ]);
        $softDeletedService->delete();

        $placesWithImages = $places->take($places->count() - 2);
        $placesWithImages->each(fn (Place $place) => $this->attachSeedMedia($place));
        $services->take($services->count() - 2)->each(fn (Service $service) => $this->attachSeedMedia($service));

        $places->take(18)->each(function (Place $place): void {
            foreach (range(0, 6) as $day) {
                OpeningHour::factory()
                    ->when($day === 5, fn ($factory) => $factory->closed())
                    ->create(['place_id' => $place->id, 'day_of_week' => $day]);
            }
        });

        $reviewers = $users->take(8)->values();
        $this->seedPlaceReviews($places->take(18), $reviewers);
        $this->seedServiceReviews($services->take(18), $reviewers);

        $places->take(12)->values()->each(function (Place $place, int $index) use ($users): void {
            Favorite::factory()->create([
                'user_id' => $users[$index % $users->count()]->id,
                'place_id' => $place->id,
            ]);
        });
        $services->take(12)->values()->each(function (Service $service, int $index) use ($users): void {
            Favorite::factory()->forService($service)->create([
                'user_id' => $users[($index + 3) % $users->count()]->id,
            ]);
        });

        PlaceSuggestion::factory()->count(6)->create([
            'place_category_id' => $placeCategories->first()->id,
        ]);
        PlaceSuggestion::factory()->approved()->count(2)->create([
            'place_category_id' => $placeCategories->get(1)->id,
        ]);
        PlaceSuggestion::factory()->rejected()->count(2)->create([
            'place_category_id' => $placeCategories->get(2)->id,
        ]);
        ServiceSuggestion::factory()->count(6)->create([
            'service_category_id' => $serviceCategories->first()->id,
        ]);
        ServiceSuggestion::factory()->approved()->count(2)->create([
            'service_category_id' => $serviceCategories->get(1)->id,
        ]);
        ServiceSuggestion::factory()->rejected()->count(2)->create([
            'service_category_id' => $serviceCategories->get(2)->id,
        ]);

        Post::factory()->count(12)->sequence(fn ($sequence) => [
            'user_id' => $admin->id,
            'published_at' => now()->subDays($sequence->index),
        ])->create();
        Post::factory()->unpublished()->count(3)->create(['user_id' => $admin->id]);
        Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'راهنمای پیدا کردن خدمات در کابل',
            'slug' => 'dari-service-guide-kabul',
            'excerpt' => 'نمونه نوشته دری برای بررسی نمایش محتوا.',
            'content' => str_repeat('این متن برای آزمایش نوشته‌های دری در مکانیاب استفاده می‌شود. ', 20),
            'is_published' => true,
            'published_at' => now(),
        ]);

        ContactMessage::factory()->count(10)->create();
    }

    private function attachSeedMedia(Place|Service $model): void
    {
        Media::factory()->cover()->forModel($model)->create();
        Media::factory()->count(2)->forModel($model)->create();
    }

    private function seedPlaceReviews(Collection $places, Collection $reviewers): void
    {
        $places->values()->each(function (Place $place, int $index) use ($reviewers): void {
            Review::factory()->create([
                'user_id' => $reviewers[$index % $reviewers->count()]->id,
                'place_id' => $place->id,
                'is_approved' => true,
            ]);
            Review::factory()->pending()->create([
                'user_id' => $reviewers[($index + 1) % $reviewers->count()]->id,
                'place_id' => $place->id,
            ]);
            Review::factory()->rejected()->create([
                'user_id' => $reviewers[($index + 2) % $reviewers->count()]->id,
                'place_id' => $place->id,
            ]);
        });
    }

    private function seedServiceReviews(Collection $services, Collection $reviewers): void
    {
        $services->values()->each(function (Service $service, int $index) use ($reviewers): void {
            Review::factory()->forService($service)->create([
                'user_id' => $reviewers[$index % $reviewers->count()]->id,
                'is_approved' => true,
            ]);
            Review::factory()->pending()->forService($service)->create([
                'user_id' => $reviewers[($index + 1) % $reviewers->count()]->id,
            ]);
            Review::factory()->rejected()->forService($service)->create([
                'user_id' => $reviewers[($index + 2) % $reviewers->count()]->id,
            ]);
        });
    }
}
