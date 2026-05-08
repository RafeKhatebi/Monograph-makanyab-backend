<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceCategory extends Model
{
    //  $fillable defines which attributes can be mass-assigned.
    protected $fillable = [
        'parent_id', 'name', 'slug', 'icon_name', 'color_code',
        'has_menu', 'has_booking', 'has_delivery',
        'keywords', 'schema_type', 'is_active', 'sort_order',
    ];

    // places() defines a one-to-many relationship with the Place model.
    public function places(): HasMany
    {

        return $this->hasMany(Place::class, 'place_category_id');
    }

    // parent() defines a self-referential belongsTo relationship to represent the parent category.
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'parent_id');
    }

    // children() defines a self-referential hasMany relationship to represent child categories.
    public function children(): HasMany
    {
        return $this->hasMany(PlaceCategory::class, 'parent_id');
    }

    // scopeActive() is a query scope that filters categories to only include those that are active.
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
