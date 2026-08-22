<?php

namespace App\Models;

use App\Enums\PlaceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Place extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'place_category_id', 'name', 'slug', 'tagline', 'description',
        'phone_1', 'phone_2', 'whatsapp', 'website', 'social_links',
        'address', 'country', 'province', 'city', 'district', 'subdistrict',
        'village', 'rt_rw', 'neighborhood', 'postal_code',
        'latitude', 'longitude',
        'status', 'price_level', 'is_verified', 'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('home_featured_places'));
        static::deleted(fn () => Cache::forget('home_featured_places'));
        static::forceDeleted(function (Place $place): void {
            $media = $place->media()->get(['disk', 'file_path']);
            $place->media()->delete();

            DB::afterCommit(function () use ($media): void {
                foreach ($media as $item) {
                    Storage::disk($item->disk ?: 'public')->delete($item->file_path);
                }
            });
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'place_category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function openingHours(): HasMany
    {
        return $this->hasMany(OpeningHour::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function coverImage(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->where('is_cover', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilterSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('tagline', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('province', 'like', "%{$search}%")
                ->orWhere('district', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('category', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('keywords', 'like', "%{$search}%");
                });
        });
    }

    public function scopeFilterCategorySlug(Builder $query, ?string $slug): Builder
    {
        if (! $slug) {
            return $query;
        }

        return $query->whereHas('category', fn (Builder $query) => $query->where('slug', $slug));
    }

    public function scopeFilterVerified(Builder $query, bool $verified = false): Builder
    {
        if (! $verified) {
            return $query;
        }

        return $query->where('is_verified', true);
    }

    public function scopeFilterOpenNow(Builder $query, bool $openNow = false): Builder
    {
        if (! $openNow) {
            return $query;
        }

        $moment = now(config('app.timezone'));
        $day = $moment->dayOfWeek;
        $previousDay = ($day + 6) % 7;
        $time = $moment->format('H:i:s');

        return $query->where(function (Builder $query) use ($day, $previousDay, $time) {
            $query->whereHas('openingHours', function (Builder $hours) use ($day, $time) {
                $hours->where('day_of_week', $day)
                    ->where('is_closed', false)
                    ->where(function (Builder $hours) use ($time) {
                        $hours->where(function (Builder $hours) use ($time) {
                            $hours->whereColumn('open_time', '<', 'close_time')
                                ->where('open_time', '<=', $time)
                                ->where('close_time', '>', $time);
                        })->orWhere(function (Builder $hours) use ($time) {
                            $hours->whereColumn('open_time', '>', 'close_time')
                                ->where('open_time', '<=', $time);
                        });
                    });
            })->orWhereHas('openingHours', function (Builder $hours) use ($previousDay, $time) {
                $hours->where('day_of_week', $previousDay)
                    ->where('is_closed', false)
                    ->whereColumn('open_time', '>', 'close_time')
                    ->where('close_time', '>', $time);
            });
        });
    }

    public function scopeFilterRatingAtLeast(Builder $query, ?int $rating): Builder
    {
        if (! $rating) {
            return $query;
        }

        return $query->whereRaw(
            '(select avg(reviews.rating) from reviews where reviews.place_id = places.id and reviews.is_approved = ? and reviews.moderation_status = ?) >= ?',
            [true, Review::STATUS_APPROVED, $rating]
        );
    }

    public function getAvgRatingAttribute(): float
    {
        return (float) ($this->reviews_avg_rating
            ?? $this->reviews()->approved()->avg('rating')
            ?? 0);
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->avg_rating;
    }

    public function getImagesAttribute(): array
    {
        if ($this->relationLoaded('media')) {
            return $this->media
                ->where('type', 'image')
                ->pluck('file_path')
                ->values()
                ->all();
        }

        return $this->media()
            ->where('type', 'image')
            ->pluck('file_path')
            ->all();
    }
}
