<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PersianDemoContentSeeder extends Seeder
{
    private const PHOTOS = [
        'restaurant' => 'photo-1517248135467-4c7edcad34c4',
        'building' => 'photo-1486406146926-c627a92ad1ab',
        'landscape' => 'photo-1500534314209-a25ddb2bd429',
        'office' => 'photo-1497366811353-6870744d04b2',
        'people' => 'photo-1521737711867-e3b97375f902',
        'books' => 'photo-1544717305-2782549b5136',
    ];

    public function run(): void
    {
        $images = $this->downloadPhotos();
        $newUsers = collect([
            ['name' => 'احمد', 'lastname' => 'نوری', 'role' => 'owner'],
            ['name' => 'مریم', 'lastname' => 'احمدی', 'role' => 'owner'],
            ['name' => 'فرید', 'lastname' => 'هاشمی', 'role' => 'owner'],
            ['name' => 'لیلا', 'lastname' => 'رحیمی', 'role' => 'owner'],
            ['name' => 'زهرا', 'lastname' => 'سادات', 'role' => 'user'],
            ['name' => 'سعید', 'lastname' => 'عزیزی', 'role' => 'user'],
            ['name' => 'فرشته', 'lastname' => 'محمدی', 'role' => 'user'],
            ['name' => 'وحید', 'lastname' => 'اکبری', 'role' => 'user'],
        ])->values()->map(function (array $person, int $index): User {
            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['email' => "demo{$number}@example.test"],
                [
                    'username' => "demo_{$number}",
                    'name' => $person['name'],
                    'lastname' => $person['lastname'],
                    'role' => $person['role'],
                    'password' => 'Demo@12345',
                    'is_active' => true,
                ],
            );

            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            return $user;
        });

        $placeCategoryNames = [
            'رستورانت', 'کافه', 'نانوایی', 'هوتل', 'مهمان‌خانه',
            'کتاب‌فروشی', 'کتابخانه', 'مرکز آموزشی', 'پارک', 'باغ',
            'موزه', 'گالری هنری', 'مرکز خرید', 'فروشگاه صنایع دستی', 'مرکز ورزشی',
        ];
        $serviceCategoryNames = [
            'ترمیم موبایل', 'ترمیم کمپیوتر', 'خدمات اینترنت', 'عکاسی', 'چاپ و طراحی',
            'خیاطی', 'آرایشگری', 'نجاری', 'برق‌کاری', 'نل‌دوانی',
            'ترانسپورت', 'خدمات آموزشی', 'ترجمه', 'نظافت', 'مشاوره کاری',
        ];

        $placeCategories = collect($placeCategoryNames)->values()->map(fn (string $name, int $index) =>
            PlaceCategory::firstOrCreate(
                ['slug' => sprintf('demo-place-category-%02d', $index + 1)],
                ['name' => $name, 'is_active' => true, 'sort_order' => $index + 1],
            )
        );
        $serviceCategories = collect($serviceCategoryNames)->values()->map(fn (string $name, int $index) =>
            ServiceCategory::firstOrCreate(
                ['slug' => sprintf('demo-service-category-%02d', $index + 1)],
                ['name' => $name, 'description' => "خدمات {$name} در شهرهای افغانستان.", 'is_active' => true, 'sort_order' => $index + 1],
            )
        );

        $placeNames = [
            'رستورانت باغ بابر', 'کافه گل سرخ', 'نانوایی آفتاب', 'هوتل آریانا', 'مهمان‌خانه کوهستان',
            'کتاب‌فروشی دانش', 'کتابخانه امید', 'آموزشگاه فردا', 'پارک سبز شهر', 'باغ گل‌ها',
            'موزه فرهنگ', 'گالری رنگین', 'مرکز خرید بهار', 'فروشگاه هنر دست', 'باشگاه تندرستی',
            'رستورانت مزه‌های هرات', 'کافه دریچه', 'نانوایی گندم', 'هوتل پامیر', 'مهمان‌خانه ابریشم',
            'کتاب‌فروشی قلم', 'کتابخانه روشنایی', 'مرکز آموزش مهارت', 'پارک دوستی', 'باغ آرامش',
        ];
        $serviceNames = [
            'ترمیم موبایل همراه', 'مرکز کمپیوتر دانا', 'اینترنت پرسرعت پیوند', 'عکاسی خاطره', 'چاپ و طراحی نقش',
            'خیاطی مهتاب', 'آرایشگاه آینه', 'نجاری چوبستان', 'برق‌کاری روشن', 'نل‌دوانی آب‌رسان',
            'ترانسپورت راه آسان', 'آموزش زبان گفتار', 'ترجمه هم‌زبان', 'نظافت خانه‌نو', 'مشاوره کاری آینده',
            'ترمیم موبایل سیمرغ', 'خدمات کمپیوتر هوشمند', 'اینترنت اتصال', 'استودیوی تصویر', 'چاپخانه رنگ',
            'خیاطی گل‌دوز', 'آرایشگاه زیبایی', 'نجاری استاد نعیم', 'برق‌کاری انرژی', 'خدمات نل‌دوانی مطمئن',
        ];

        $locations = json_decode(file_get_contents(resource_path('data/afghanistan-locations.json')), true)['provinces'];
        $locationKeys = ['Kabul', 'Herat', 'Balkh', 'Bamyan', 'Kandahar', 'Nangarhar'];
        $locationDistricts = ['Kabul', 'Hirat', 'Balkh', 'Bamyan', 'Kandahar', 'Behsud'];

        foreach ($placeNames as $index => $name) {
            $locationIndex = $index % count($locationKeys);
            $province = $locationKeys[$locationIndex];
            $district = $locationDistricts[$locationIndex];
            $location = collect($locations)->firstWhere('value', $province);
            $category = $placeCategories[$index % $placeCategories->count()];
            $place = Place::firstOrCreate(
                ['slug' => sprintf('demo-place-%02d', $index + 1)],
                [
                    'user_id' => $newUsers[$index % 4]->id,
                    'place_category_id' => $category->id,
                    'name' => $name,
                    'tagline' => 'نمونه برای آشنایی با بخش مکان‌ها',
                    'description' => "{$name} یک مکان نمونه برای بررسی جستجو، دسته‌بندی و نمایش جزئیات در مکانیاب است. اطلاعات تماس و موقعیت این مورد آزمایشی است.",
                    'phone_1' => '0700000000',
                    'country' => 'Afghanistan',
                    'province' => $province,
                    'city' => $district,
                    'district' => $district,
                    'latitude' => $location['center'][0],
                    'longitude' => $location['center'][1],
                    'status' => 'open',
                    'price_level' => ['low', 'medium', 'high'][$index % 3],
                    'is_verified' => $index % 4 === 0,
                    'is_active' => true,
                ],
            );
            $this->attachImage($place, $images[array_keys(self::PHOTOS)[$index % count(self::PHOTOS)]] ?? null);
        }

        foreach ($serviceNames as $index => $name) {
            $locationIndex = $index % count($locationKeys);
            $province = $locationKeys[$locationIndex];
            $district = $locationDistricts[$locationIndex];
            $location = collect($locations)->firstWhere('value', $province);
            $category = $serviceCategories[$index % $serviceCategories->count()];
            $service = Service::firstOrCreate(
                ['slug' => sprintf('demo-service-%02d', $index + 1)],
                [
                    'user_id' => $newUsers[$index % 4]->id,
                    'service_category_id' => $category->id,
                    'name' => $name,
                    'tagline' => 'نمونه برای آشنایی با بخش خدمات',
                    'description' => "{$name} یک خدمت نمونه برای بررسی جستجو، دسته‌بندی و نمایش جزئیات در مکانیاب است. اطلاعات تماس این مورد آزمایشی است.",
                    'phone_1' => '0700000000',
                    'country' => 'Afghanistan',
                    'province' => $province,
                    'city' => $district,
                    'district' => $district,
                    'latitude' => $location['center'][0],
                    'longitude' => $location['center'][1],
                    'status' => 'open',
                    'price_level' => ['low', 'medium', 'high'][$index % 3],
                    'is_verified' => $index % 5 === 0,
                    'is_active' => true,
                ],
            );
            $this->attachImage($service, $images[array_keys(self::PHOTOS)[($index + 3) % count(self::PHOTOS)]] ?? null);
        }

        $postTitles = [
            'راهنمای پیدا کردن مکان‌های نزدیک', 'چگونه یک خدمت مناسب انتخاب کنیم',
            'نگاهی به کتابخانه‌های شهر', 'پیشنهادهایی برای گردش در آخر هفته',
            'آشنایی با صنایع دستی افغانستان', 'راهنمای ثبت مکان در مکانیاب',
            'چگونه دیدگاه مفید بنویسیم', 'معرفی خدمات آموزشی محلی',
        ];
        foreach ($postTitles as $index => $title) {
            Post::firstOrCreate(
                ['slug' => sprintf('demo-post-%02d', $index + 1)],
                [
                    'user_id' => $newUsers[$index % $newUsers->count()]->id,
                    'title' => $title,
                    'excerpt' => "در این نوشتهٔ نمونه، {$title} را به زبان ساده مرور می‌کنیم.",
                    'content' => "# {$title}\n\nاین نوشته برای نمایش و بررسی بخش مطالب مکانیاب آماده شده است. برای انتخاب بهتر، مشخصات هر مورد را بخوانید و تجربهٔ دیگر کاربران را نیز بررسی کنید.\n\nاگر اطلاعات تازه‌ای دارید، می‌توانید آن را با دیگران شریک سازید.",
                    'image' => $images[array_keys(self::PHOTOS)[($index + 2) % count(self::PHOTOS)]] ?? null,
                    'submission_status' => 'published',
                    'is_published' => true,
                    'published_at' => now()->subDays($index),
                ],
            );
        }

        $reviewers = User::query()->orderBy('created_at')->get();
        $comments = [
            'محیط خوب و برخورد کارکنان دوستانه بود.',
            'اطلاعات صفحه برای پیدا کردن این مورد کمک‌کننده است.',
            'تجربهٔ خوبی داشتم و دوباره مراجعه می‌کنم.',
            'دسترسی به این محل آسان بود و خدمات مناسب ارائه شد.',
            'بهتر است ساعت‌های کاری نیز به‌روز نگه داشته شود.',
        ];
        foreach (Place::query()->where('slug', 'like', 'demo-place-%')->orderBy('slug')->get() as $index => $place) {
            $reviewer = $reviewers[($index + 2) % $reviewers->count()];
            Review::firstOrCreate(
                ['user_id' => $reviewer->id, 'place_id' => $place->id],
                [
                    'rating' => 3 + ($index % 3),
                    'comment' => $comments[$index % count($comments)],
                    'moderation_status' => $index % 5 === 0 ? Review::STATUS_PENDING : Review::STATUS_APPROVED,
                ],
            );
        }
        foreach (Service::query()->where('slug', 'like', 'demo-service-%')->orderBy('slug')->get() as $index => $service) {
            $reviewer = $reviewers[($index + 4) % $reviewers->count()];
            Review::firstOrCreate(
                ['user_id' => $reviewer->id, 'service_id' => $service->id],
                [
                    'rating' => 3 + ($index % 3),
                    'comment' => $comments[($index + 2) % count($comments)],
                    'moderation_status' => $index % 5 === 0 ? Review::STATUS_PENDING : Review::STATUS_APPROVED,
                ],
            );
        }

        $subjects = [
            'پرسش دربارهٔ ثبت مکان', 'اصلاح اطلاعات تماس', 'پیشنهاد دسته‌بندی تازه',
            'راهنمایی دربارهٔ حساب کاربری', 'مشکل در نمایش تصویر',
            'درخواست ویرایش خدمت', 'پرسش دربارهٔ دیدگاه‌ها', 'پیشنهاد برای جستجو',
            'گزارش اطلاعات قدیمی', 'سپاس از تیم مکانیاب',
        ];
        foreach ($subjects as $index => $subject) {
            $user = $reviewers[$index % $reviewers->count()];
            ContactMessage::firstOrCreate(
                ['email' => $user->email, 'subject' => $subject],
                [
                    'user_id' => $user->id,
                    'name' => trim($user->name.' '.$user->lastname),
                    'telephone' => '0700000000',
                    'message' => "سلام، این پیام نمونه دربارهٔ «{$subject}» برای بررسی بخش پیام‌های مکانیاب ثبت شده است. لطفاً راهنمایی کنید.",
                    'read_at' => $index % 3 === 0 ? now() : null,
                ],
            );
        }
    }

    private function downloadPhotos(): array
    {
        $files = [];
        foreach (self::PHOTOS as $kind => $photoId) {
            $path = "demo-photos/{$kind}.jpg";
            if (! Storage::disk('public')->exists($path)) {
                try {
                    $response = Http::timeout(15)->get("https://images.unsplash.com/{$photoId}", ['w' => 900, 'q' => 75]);
                    if ($response->successful() && str_starts_with($response->header('Content-Type', ''), 'image/')) {
                        Storage::disk('public')->put($path, $response->body());
                    }
                } catch (\Throwable $exception) {
                    $this->command?->warn("تصویر {$kind} دریافت نشد: {$exception->getMessage()}");
                }
            }
            if (Storage::disk('public')->exists($path)) {
                $files[$kind] = $path;
            }
        }

        return $files;
    }

    private function attachImage(Place|Service $model, ?string $path): void
    {
        if (! $path || $model->media()->exists()) {
            return;
        }

        $model->media()->create([
            'file_path' => $path,
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => Storage::disk('public')->size($path),
            'type' => 'image',
            'is_cover' => true,
            'sort_order' => 0,
        ]);
    }
}
