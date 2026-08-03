<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class PlaceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'icon_name', 'color_code',
        'has_menu', 'has_booking', 'has_delivery',
        'keywords', 'schema_type', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'has_menu' => 'boolean',
        'has_booking' => 'boolean',
        'has_delivery' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function places(): HasMany
    {
        return $this->hasMany(Place::class, 'place_category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PlaceCategory::class, 'parent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active categories with caching (rarely change).
     */
    public static function getActiveCached()
    {
        return Cache::remember('active_place_categories_list', 1800, function () {
            return static::active()->orderBy('name')->get();
        });
    }

    /**
     * Clear category cache when categories are updated.
     */
    public static function clearCache(): void
    {
        Cache::forget('active_place_categories_list');
        Cache::forget('active_place_categories');
        Cache::forget('home_categories');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
