<?php

namespace App\Models;

use App\Enums\PlaceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\q2HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
// TODO: Add filter for category_id
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // TODO: Add filter for category_id
    public function scopeFilterSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('tagline', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
    // TODO: Add filter for category_id

    public function scopeFilterCategorySlug(Builder $query, ?string $slug): Builder
    {
        if (! $slug) {
            return $query;
        }

        return $query->whereHas('category', fn (Builder $query) => $query->where('slug', $slug));
    }
    // TODO: Add filter for category_id

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

        return $query->where('status', PlaceStatus::Open);
    }

    public function scopeFilterRatingAtLeast(Builder $query, ?int $rating): Builder
    {
        if (! $rating) {
            return $query;
        }

        return $query->whereHas('reviews', function (Builder $query) use ($rating) {
            $query->where('is_approved', true)
                ->where('rating', '>=', $rating);
        });
    }

    public function getAvgRatingAttribute(): float
    {
        return (float) ($this->reviews_avg_rating ?? $this->reviews()->avg('rating') ?? 0);
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
