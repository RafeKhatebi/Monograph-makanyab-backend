<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'icon_name', 'color_code',
        'has_menu', 'has_booking', 'has_delivery',
        'keywords', 'schema_type', 'is_active', 'sort_order',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'service_category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id');
    }
}
