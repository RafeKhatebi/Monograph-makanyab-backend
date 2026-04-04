<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceCategory extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'icon_name', 'color_code',
        'has_menu', 'has_booking', 'has_delivery',
        'keywords', 'schema_type', 'is_active', 'sort_order',
    ];

    public function places(): HasMany
    {
        return $this->hasMany(Place::class, 'place_category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'parent_id');
    }
}
